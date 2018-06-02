<?php

namespace App\Command;

use App\Entity\Element;
use App\Entity\Ion;
use App\Entity\Spectrum;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AppParseCommand
 * todo разбить на разные классы по разделению отпветственности
 */
class AppParseCommand extends Command
{
    const HOLDINGS_URL = 'https://physics.nist.gov/cgi-bin/ASD/lines_hold.pl';
    const LINES_URL = 'https://physics.nist.gov/cgi-bin/ASD/lines1.pl?unit=1&line_out=0&bibrefs=1&show_obs_wl=1&show_calc_wl=1&A_out=0&intens_out=1&allowed_out=1&forbid_out=1&conf_out=1&term_out=1&enrg_out=1&J_out=1&g_out=0';
    const PT_URL = 'https://physics.nist.gov/cgi-bin/ASD/lines_pt.pl';

    protected $elements = array(
        0 => 'H',
        1 => 'He',
        2 => 'Li',
        3 => 'Be',
        4 => 'B',
        5 => 'C',
        6 => 'N',
        7 => 'O',
        8 => 'F',
        9 => 'Ne',
        10 => 'Na',
        11 => 'Mg',
        12 => 'Al',
        13 => 'Si',
        14 => 'P',
        15 => 'S',
        16 => 'Cl',
        17 => 'Ar',
        18 => 'K',
        19 => 'Ca',
        20 => 'Sc',
        21 => 'Ti',
        22 => 'V',
        23 => 'Cr',
        24 => 'Mn',
        25 => 'Fe',
        26 => 'Co',
        27 => 'Ni',
        28 => 'Cu',
        29 => 'Zn',
        30 => 'Ga',
        31 => 'Ge',
        32 => 'As',
        33 => 'Se',
        34 => 'Br',
        35 => 'Kr',
        36 => 'Rb',
        37 => 'Sr',
        38 => 'Y',
        39 => 'Zr',
        40 => 'Nb',
        41 => 'Mo',
        42 => 'Tc',
        43 => 'Ru',
        44 => 'Rh',
        45 => 'Pd',
        46 => 'Ag',
        47 => 'Cd',
        48 => 'In',
        49 => 'Sn',
        50 => 'Sb',
        51 => 'Te',
        52 => 'I',
        53 => 'Xe',
        54 => 'Cs',
        55 => 'Ba',
        56 => 'Hf',
        57 => 'Ta',
        58 => 'W',
        59 => 'Re',
        60 => 'Os',
        61 => 'Ir',
        62 => 'Pt',
        63 => 'Au',
        64 => 'Hg',
        65 => 'Tl',
        66 => 'Pb',
        67 => 'Bi',
        68 => 'Po',
        69 => 'At',
        70 => 'Rn',
        71 => 'Fr',
        72 => 'Ra',
        73 => 'La',
        74 => 'Ce',
        75 => 'Pr',
        76 => 'Nd',
        77 => 'Pm',
        78 => 'Sm',
        79 => 'Eu',
        80 => 'Gd',
        81 => 'Tb',
        82 => 'Dy',
        83 => 'Ho',
        84 => 'Er',
        85 => 'Tm',
        86 => 'Yb',
        87 => 'Lu',
        88 => 'Ac',
        89 => 'Th',
        90 => 'Pa',
        91 => 'U',
        92 => 'Np',
        93 => 'Pu',
        94 => 'Am',
        95 => 'Cm',
        96 => 'Bk',
        97 => 'Cf',
        98 => 'Es',
    );

    /** @var Client */
    protected $httpClient;

    protected $headers = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
    ];

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * AppParseCommand constructor.
     * @param EntityManagerInterface $em
     * @param null $name
     */
    public function __construct(EntityManagerInterface $em, $name = null)
    {
        parent::__construct('nist:parse');
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Parses the NIST Atomic Spectra Database')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to...')
            // configure an argument
            ->addArgument('el', InputArgument::IS_ARRAY, 'Elements (separate multiple names with a space)');
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

//        sleep(random_int(3,7));

        try {
            $response = $this->httpClient->request('GET', $url, [
                RequestOptions::HEADERS => array_merge($this->headers, ['Referer' => $referrer]),
                RequestOptions::TIMEOUT => 200,
            ]);
        } catch (\Throwable $e) {
            // todo: logging
            throw $e;
        }

        return $response->getBody()->getContents();
    }


    /**
     * {@inheritdoc}
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $elements = $input->getArgument('el')
            ? array_intersect($input->getArgument('el'), $this->getElements())
            : $this->getElements();

        $repository = $this->em->getRepository(Element::class);
        $all = $repository->findAll();
        $dbElements = array_map(function ($item) {
            return $item->getTitle();
        }, $all);
//        todo сначала распарсить все ионы, и посчитать сколько всего линий и выводить их количество в прогресс-баре
        $progressBar = new ProgressBar($output, \count(array_diff($elements, $dbElements)));
        $progressBar->start();

        foreach ($elements as $number => $element) {
            if (array_keys($dbElements, $element)) {
                continue;
            }
            $el = new Element($element, $number);
            $rows = $this->getIons(self::HOLDINGS_URL . '?el=' . $el->getTitle());

            foreach ($rows as $row) {
                $ion = new Ion($row[0]);
                $el->addIon($ion);
                [$headers, $records] = $this->getSpectra($ion->getTitle());
                array_map(function ($fields, $records) use ($ion) {
                    foreach ($records as $values) {
                        $combined = array_combine($fields, $values);
                        $spectrum = new Spectrum($combined);
                        $ion->addSpectrum($spectrum);
                    }
                }, $headers, $records);
            }

            $this->em->persist($el);
            $this->em->flush();
            $this->em->clear();

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
     * @param string $spectra
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    protected function getSpectra(string $spectra): array
    {
        $referrer = self::HOLDINGS_URL . '?el=' . $spectra;
        $url = self::LINES_URL . '&spectra=' . rawurlencode($spectra);
        $html2 = $this->httpRequest($url, $referrer);
        $crawler = new Crawler($html2);
        $headers = $this->parseHeaders($crawler);
        $rows = $this->parseRows($crawler, \count($headers[0]));
        return [$headers, $rows];
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
                $trimmed = trim(
                    trim(
                        str_replace(
                            '\\u00a0',
                            '',
                            strip_tags($node->text())
                        ),
                        \chr(0xC2) . \chr(0xA0)
                    )
                );
                return \substr_count($trimmed, ', ') ? explode(', ', $trimmed) : [$trimmed];
            });
            return $temps ? array_merge(...$temps) : null;
        }));
        return $headers;
    }

    /**
     * @param Crawler $crawler
     * @param int $chunk
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function parseRows(Crawler $crawler, int $chunk): array
    {
        $data = array_filter($crawler->filter('tbody')->each(function (Crawler $node, $i) use ($chunk) {
            $oddRows = array_chunk($node->children()->filter('tr.odd  > td')->each(function (Crawler $node, $i) {
                return trim(trim($node->text()), \chr(0xC2) . \chr(0xA0));
            }), $chunk);
            $envRows = array_chunk($node->children()->filter('tr.evn  > td')->each(function (Crawler $node, $i) {
                return trim(trim($node->text()), \chr(0xC2) . \chr(0xA0));
            }), $chunk);
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
        $referrer = self::PT_URL;
        $html = $this->httpRequest($url, $referrer);
        $crawler = new Crawler($html);
        return $this->parseIons($crawler);
    }

    /**
     * @param bool $variable
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    protected function getElements(bool $variable = true): array
    {
        if ($variable) {
            return $this->elements;
        }
        $html = $this->httpRequest(self::PT_URL);
        $crawler = new Crawler($html);
        $elements = $crawler->filter('td > a.pth')->each(function (Crawler $node, $i) {
            return $node->attr('id');
        });
//        todo парсить номера элементов
//        $numbers = $crawler->filter('td > sup')->each(function (Crawler $node, $i) {
//            return $node->text();
//        });
//        return array_combine($numbers, $elements);
        return $elements;
    }
}
