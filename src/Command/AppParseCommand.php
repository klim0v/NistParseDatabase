<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AppParseCommand
 */
class AppParseCommand extends Command
{

    /** @var Client */
    protected $httpClient;

    protected $headers = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
    ];

    /**
     *
     */
    protected function configure()
    {
        $this
            ->addArgument('el', InputArgument::REQUIRED, 'element')
        ;
    }

    public function __construct()
    {
        parent::__construct('nist:parse');
    }

    /**
     * @param string $url
     * @param string|null $referrer
     * @return string
     * @throws \RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    protected function httpRequest(string $url, string $referrer = null): string
    {
        if (null === $this->httpClient) {
            $this->httpClient = new Client();
        }

        sleep(1);

        try {
            $response = $this->httpClient->request('GET', $url, [
                RequestOptions::HEADERS => array_merge($this->headers, ['Referer' => $referrer]),
                RequestOptions::TIMEOUT => 150,
            ]);
        } catch (\Throwable $e) {
            // todo: logging
            throw $e;
        }

        return $response->getBody()->getContents();
    }


    /**
     * {@inheritdoc}
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $elements = [$input->getArgument('el')] ?? $this->getElements();

        $progressBar = new ProgressBar($output, \count($elements));
        $progressBar->start();

        foreach ($elements as $element) {
            $url = 'https://physics.nist.gov/cgi-bin/ASD/lines_hold.pl?el=' . $element;
            $rows = $this->getIons($url);

            $tableIons = new Table($output);
            $tableIons->setHeaders([
                    'Ion',
                    'No. of lines',
                    'Lines with transition probabilities',
                    'Lines with level designations'
                ])->setRows($rows);


            $progressBar->clear();
            $tableIons->render();
            $progressBar->display();

            foreach ($rows as $row) {
                [$rows, $headers] = $this->getSpectra($url, $row[0]);

                $progressBar->clear();

                $tableLines = new Table($output);
                array_map(function ($v1, $v2) use ($tableLines) {
                    $tableLines->setHeaders($v1)
                        ->setRows($v2);
                    $tableLines->render();
                }, $headers, $rows);

                $progressBar->display();
            }
            return 1;
            $progressBar->advance();
        }
        $progressBar->finish();

        return 0;
    }

    /**
     * @param $crawler
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function parseIons(Crawler $crawler): array
    {
        return array_chunk($crawler
            ->filter('td > b')->each(function (Crawler $node, $i) {
                return trim($node->parents()->text());
            }), 4);
    }

    /**
     * @param string $referrer
     * @param string $spectra
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    protected function getSpectra(string $referrer, string $spectra): array
    {
        $url = 'https://physics.nist.gov/cgi-bin/ASD/lines1.pl?unit=1&line_out=0&bibrefs=1&show_obs_wl=1&show_calc_wl=1&A_out=0&intens_out=1&allowed_out=1&forbid_out=1&conf_out=1&term_out=1&enrg_out=1&J_out=1&g_out=0&spectra=' . rawurlencode($spectra);
        $html2 = $this->httpRequest($url, $referrer);
        $crawler = new Crawler($html2);
        $headers = $this->parseHeaders($crawler);
        $rows = $this->parseRows($crawler, $headers);
        return [
            $rows,
            $headers
        ];
    }

    /**
     * @param Crawler $crawler
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function parseHeaders(Crawler $crawler): array
    {
        $headers = array_filter($crawler->filter('tbody > tr')->each(function (Crawler $node, $i) {
            $temps = $node->children()->filter('th')->each(function (Crawler $node, $i) {
                $trimmed = trim(trim(strip_tags($node->text())), \chr(0xC2) . \chr(0xA0));
                return \substr_count($trimmed, ', ') ? explode(', ', $trimmed) : [$trimmed];
            });
            return $temps ? array_merge(...$temps) : null;
        }));
        return $headers;
    }

    /**
     * @param Crawler $crawler
     * @param array $headers
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function parseRows(Crawler $crawler, array $headers): array
    {
        $data = array_filter($crawler->filter('tbody')->each(function (Crawler $node, $i) use ($headers) {
            $oddRows = array_chunk($node->children()->filter('tr.odd  > td')->each(function (Crawler $node, $i) {
                return trim(trim($node->text()), \chr(0xC2) . \chr(0xA0));
            }), \count($headers[0]));
            $envRows = array_chunk($node->children()->filter('tr.evn  > td')->each(function (Crawler $node, $i) {
                return trim(trim($node->text()), \chr(0xC2) . \chr(0xA0));
            }), \count($headers[0]));
            return array_merge($oddRows, $envRows);
        }));
        return $data;
    }

    /**
     * @param $url
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    protected function getIons($url): array
    {
        $referrer = 'https://physics.nist.gov/cgi-bin/ASD/lines_pt.pl';
        $html = $this->httpRequest($url, $referrer);
        $crawler = new Crawler($html);
        return $this->parseIons($crawler);
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    protected function getElements(): array
    {
        $html = $this->httpRequest('https://physics.nist.gov/cgi-bin/ASD/lines_pt.pl');
        $crawler = new Crawler($html);
        $elements = $crawler->filter('td > a.pth')->each(function (Crawler $node, $i) {
            return $node->attr('id');
        });
        return $elements;
    }
}
