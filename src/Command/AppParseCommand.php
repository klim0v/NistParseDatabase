<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
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

    public function __construct()
    {
        parent::__construct('nist:parse');
    }

    /**
     * @param string $url
     * @param string $referrer
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    protected function httpRequest(string $url, string $referrer): string
    {
        if (null === $this->httpClient) {
            $this->httpClient = new Client();
        }

        sleep(1);

        try {
            $response = $this->httpClient->request('GET', $url, [
                RequestOptions::HEADERS => array_merge($this->headers, ['Referer' => $referrer]),
                RequestOptions::TIMEOUT => 15,
            ]);
        } catch (\Throwable $e) {
            // todo: logging
            throw $e;
        }

        return $response->getBody()->getContents();
    }


    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <table>
            <tr valign="top" />
                <th align="left">Ion</th>
                <th>&nbsp;</th>
                <th align="right">No. of lines</th>
                <th>&nbsp;</th>
                <th align="right">Lines with<br>transition<br>probabilities</th>
                <th>&nbsp;</th>
                <th align="right">Lines with<br>level<br>designations</th>
            <tr />
                <td colspan="8"><hr /></td>
            <tr />
                <!--<td><b><a class="bib" id="aa" name="Cf%20I">Cf I</a></b></td>-->
                <!--<td>&nbsp;</td>-->
                <!--<td align="right"><b>26</b></td>-->
                <!--<td>&nbsp;</td>-->
                <!--<td align="right"><b>0</b></td>-->
                <!--<td>&nbsp;</td>-->
                <!--<td align="right"><b>0</b></td>-->
            <tr />
                <td><b><a class="bib" id="aa" name="Cf%20II">Cf II</a></b></td>
                <td>&nbsp;</td>
                <td align="right"><b>10</b></td>
                <td>&nbsp;</td>
                <td align="right"><b>0</b></td>
                <td>&nbsp;</td>
                <td align="right"><b>0</b></td>
            <tr />
                <td colspan="8"><hr /></td>
            <tr />
                <td>Total:</td>
                <td>&nbsp;</td>
                <td align="right">36</td>
            <td>&nbsp;</td>
            <td align="right">0</td>
            <td>&nbsp;</td>
            <td align="right">0</td>
        </table>
    </body>
</html>
HTML;

        $html2 = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <tr class='odd'>

 <td class="fix">        361.211&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='odd'>

 <td class="fix">        362.676&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000<a class='bal' href="javascript:void(toggleBalloon('s'))" onmouseover="showBalloon('s',event);self.status='Help';return true" onmouseout="hideBalloon('s',event);self.status='';return true">s</a>&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='odd'>

 <td class="fix">        372.211&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000<a class='bal' href="javascript:void(toggleBalloon('l'))" onmouseover="showBalloon('l',event);self.status='Help';return true" onmouseout="hideBalloon('l',event);self.status='';return true">l</a>&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='odd'>

 <td class="fix">        378.904&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000<a class='bal' href="javascript:void(toggleBalloon('l'))" onmouseover="showBalloon('l',event);self.status='Help';return true" onmouseout="hideBalloon('l',event);self.status='';return true">l</a>&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='odd'>

 <td class="fix">        389.323&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000<a class='bal' href="javascript:void(toggleBalloon('s'))" onmouseover="showBalloon('s',event);self.status='Help';return true" onmouseout="hideBalloon('s',event);self.status='';return true">s</a>&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">        399.357&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000<a class='bal' href="javascript:void(toggleBalloon('l'))" onmouseover="showBalloon('l',event);self.status='Help';return true" onmouseout="hideBalloon('l',event);self.status='';return true">l</a>&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='evn'>

 <td class="fix">        692.710&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='evn'>

 <td class="fix">        833.385&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='evn'>

 <td class="fix">        842.349&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>


<tr class='evn'>

 <td class="fix">        856.883&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt">&nbsp;10000&nbsp;</td>
 <td class="lft1">&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="dsh">&nbsp;</td>
 <td class="fix">&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib">&nbsp;</td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Cf II'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=&amp;comment_code=u11&amp;element=Cf&amp;spectr_charge=2&amp;ref=&amp;type=');return false">u11</a></td>

</tr>
    </body>
</html>
HTML;


        $elements = [
            'At',
            'Cf',
        ];

        foreach ($elements as $element) {
            $url_1 = 'https://physics.nist.gov/cgi-bin/ASD/lines_hold.pl?el=' . $element;
            $referrer = 'https://physics.nist.gov/cgi-bin/ASD/lines_pt.pl';
            $html = $this->httpRequest($url_1, $referrer);
            $crawler = new Crawler($html);
            $rows = $this->parseLines($crawler);

            $table = new Table($output);
            $table
                ->setHeaders(array('Ion', 'No. of lines', 'Lines with transition probabilities', 'Lines with level designations'))
                ->setRows($rows);
            $table->render();
            foreach ($rows as $row) {
                $referrer = $url_1;
                $url = 'https://physics.nist.gov/cgi-bin/ASD/lines1.pl?unit=1&line_out=0&bibrefs=1&show_obs_wl=1&show_calc_wl=1&A_out=0&intens_out=1&allowed_out=1&forbid_out=1&conf_out=1&term_out=1&enrg_out=1&J_out=1&g_out=0&spectra=' . rawurlencode($row[0]);
                $html2 = $this->httpRequest($url, $referrer);
                $crawler = new Crawler($html2);
                $a = $crawler->filter('tr  > th');
                $advanced = \count(array_filter($a->extract(['_text' => 'colspan'])));

                $oddRows = array_chunk($crawler->filter('tr.odd  > td')->each(function (Crawler $node, $i) {
                    return trim(trim($node->text()), \chr(0xC2). \chr(0xA0));
                }), 11 + $advanced*3);
                $envRows = array_chunk($crawler->filter('tr.evn  > td')->each(function (Crawler $node, $i) {
                    return trim(trim($node->text()), \chr(0xC2). \chr(0xA0));
                }), 11 + $advanced*3);
                $rows = array_merge($oddRows, $envRows);
                $table = new Table($output);
                $headers[0] = [
                    'Observed Wavelength Air (nm)',
                    'Ritz Wavelength Air (nm)',
                    'Rel. Int. (?)',
                    'Aki (s-1)',
                    'Acc.',
                    'Ei (cm-1)',
                    '-',
                    'Ek (cm-1)',
                ];
                $headers[1] = $advanced ? ['Lower Level Conf., Term, J','','','Upper Level Conf., Term, J','',''] : [];
                $headers[2] = [
                    'Type',
                    'TP Ref.',
                    'Line Ref.'
                ];
                $table
                    ->setHeaders(array_merge(...$headers))
                    ->setRows($rows);
                $table->render();

            }

        }


        /*
                // creates a new progress bar (50 units)
                $progressBar = new ProgressBar($output, 50);

                // starts and displays the progress bar
                $progressBar->start();

                $i = 0;
                while ($i++ < 50) {
                    // ... do some work

                    // advances the progress bar 1 unit
                    $progressBar->advance();

                    // you can also advance the progress bar by more than 1 unit
                    // $progressBar->advance(3);
                }

                // ensures that the progress bar is at 100%
                $progressBar->finish();
        */

        return 0;
    }

    /**
     * @param $crawler
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function parseLines(Crawler $crawler): array
    {
        return array_chunk($crawler
            ->filter('td > b')->each(function (Crawler $node, $i) {
                return trim($node->parents()->text());
            }), 4);
    }
}
