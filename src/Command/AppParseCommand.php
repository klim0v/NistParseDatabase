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

    public $htmlIons = <<<'HTML'
    <!DOCTYPE html>
    <html>
        <body>
            <table>
<tr valign="top" /><th align="left">Ion</th><th>&nbsp;</th><th align="right">No. of lines</th><th>&nbsp;</th><th align="right">Lines with<br>transition<br>probabilities</th><th>&nbsp;</th><th align="right">Lines with<br>level<br>designations</th>
<tr /><td colspan="8"><hr /></td>
<tr /><td><b><a class="bib" id="aa" name="Fr%20I">Fr I</a></b></td><td>&nbsp;</td><td align="right"><b>149</b></td><td>&nbsp;</td><td align="right"><b>149</b></td><td>&nbsp;</td><td align="right"><b>149</b></td>
<tr /><td colspan="8"><hr /></td>
<tr /><td>Total:</td><td>&nbsp;</td><td align="right">149</td>
<td>&nbsp;</td><td align="right">149</td><td>&nbsp;</td><td align="right">149</td></table>
        </body>
    </html>
HTML;
    public $htmlSpectra = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
    <tbody>
<tr style="vertical-align:middle; background-color:#177A9C">
<th scope="col" style="text-align:center">&nbsp;Observed&nbsp;<br />&nbsp;Wavelength&nbsp;<br />&nbsp;Air (nm)&nbsp;</th>
<th scope="col" style="text-align:center">&nbsp;Unc.&nbsp;<br />&nbsp;(nm)&nbsp;</th>
<th scope="col" style="text-align:center">&nbsp;Ritz&nbsp;<br />&nbsp;Wavelength&nbsp;<br />&nbsp;Air (nm)&nbsp;</th>
<th scope="col" style="text-align:center">&nbsp;Rel.&nbsp;<br />&nbsp;Int.&nbsp;<br /><a class='bal' href="javascript:void(toggleBalloon('intensity'))" onmouseover="showBalloon('intensity',event);self.status='Help';return true" onmouseout="hideBalloon('intensity',event);self.status='';return true">(?) </a></th>
<th scope="col" style="text-align:center; white-space:nowrap">&nbsp;<i>A<sub>ki</sub></i><br />&nbsp;(s<sup>-1</sup>)&nbsp;</th>
<th scope="col">&nbsp;Acc.&nbsp;</th>
<th scope="col" style="text-align:center; white-space:nowrap">&nbsp;<i>E<sub>i</sub></i>&nbsp;<br />&nbsp;(cm<sup>-1</sup>)&nbsp;</th>
<th>&nbsp;</th>
<th scope="col" style="text-align:center; white-space:nowrap">&nbsp;<i>E<sub>k</sub></i>&nbsp;<br />&nbsp;(cm<sup>-1</sup>)&nbsp;</th>
<th scope="col" style="text-align:center" colspan="3">&nbsp;Lower Level&nbsp;<br />&nbsp;Conf.,&nbsp;Term,&nbsp;J&nbsp;</th>
<th scope="col" style="text-align:center" colspan="3">&nbsp;Upper Level&nbsp;<br />&nbsp;Conf.,&nbsp;Term,&nbsp;J&nbsp;</th>
<th scope="col" style="text-align:center">&nbsp;Type&nbsp;</th>
<th scope="col" style="text-align:center">&nbsp;TP<br />Ref.</th>
<th scope="col" style="text-align:center">&nbsp;Line<br />Ref.</th>
</tr>
</tbody>

<tbody>
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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          308.6287&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          308.6287&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">8.00e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000068" class="en_span" onclick="selectById('087001.000068')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 391.99&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          308.6843&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          308.6843&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">8.01e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000067" class="en_span" onclick="selectById('087001.000067')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 386.15&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          309.2514&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          309.2514&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.00e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000063" class="en_span" onclick="selectById('087001.000063')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 326.77&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          309.3197&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          309.3197&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.82e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000062" class="en_span" onclick="selectById('087001.000062')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 319.63&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          310.0211&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          310.0211&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.23e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000058" class="en_span" onclick="selectById('087001.000058')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 246.51&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          310.1063&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          310.1063&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.23e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000057" class="en_span" onclick="selectById('087001.000057')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 237.65&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          310.9885&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          310.9885&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.57e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000053" class="en_span" onclick="selectById('087001.000053')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 146.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          311.0966&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          311.0966&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.57e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000052" class="en_span" onclick="selectById('087001.000052')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 135.03&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          312.2284&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          312.2284&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.10e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000048" class="en_span" onclick="selectById('087001.000048')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 018.55&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          312.3684&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          312.3684&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.11e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000047" class="en_span" onclick="selectById('087001.000047')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 004.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          313.8547&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          313.8547&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.81e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000043" class="en_span" onclick="selectById('087001.000043')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 852.65&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          314.0409&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          314.0409&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.81e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000042" class="en_span" onclick="selectById('087001.000042')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 833.76&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          316.0492&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          316.0492&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.00e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000038" class="en_span" onclick="selectById('087001.000038')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 631.49&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          316.3046&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          316.3046&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.91e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000037" class="en_span" onclick="selectById('087001.000037')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 605.95&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          319.1165&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          319.1165&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">5.81e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000033" class="en_span" onclick="selectById('087001.000033')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 327.46&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          319.4810&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          319.4810&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">5.81e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000032" class="en_span" onclick="selectById('087001.000032')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 291.72&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          323.6029&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          323.6029&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.16e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000028" class="en_span" onclick="selectById('087001.000028')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 893.16&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          324.1504&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          324.1504&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.15e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000027" class="en_span" onclick="selectById('087001.000027')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 840.98&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          330.5752&nbsp;&nbsp;</td>
 <td class="fix">0.0005&nbsp;&nbsp;</td>
 <td class="fix">          330.5752&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.56e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000023" class="en_span" onclick="selectById('087001.000023')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 241.60&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          331.4578&nbsp;&nbsp;</td>
 <td class="fix">0.0006&nbsp;&nbsp;</td>
 <td class="fix">          331.4578&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.56e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000022" class="en_span" onclick="selectById('087001.000022')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 161.07&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          342.3900&nbsp;&nbsp;</td>
 <td class="fix">0.0006&nbsp;&nbsp;</td>
 <td class="fix">          342.3900&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.04e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000018" class="en_span" onclick="selectById('087001.000018')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 198.09&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          343.9675&nbsp;&nbsp;</td>
 <td class="fix">0.0006&nbsp;&nbsp;</td>
 <td class="fix">          343.9675&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.02e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000017" class="en_span" onclick="selectById('087001.000017')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 064.18&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          365.3102&nbsp;&nbsp;</td>
 <td class="fix">0.0007&nbsp;&nbsp;</td>
 <td class="fix">          365.3102&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.52e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000013" class="en_span" onclick="selectById('087001.000013')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 366.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          368.6510&nbsp;&nbsp;</td>
 <td class="fix">0.0007&nbsp;&nbsp;</td>
 <td class="fix">          368.6510&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.24e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000012" class="en_span" onclick="selectById('087001.000012')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 118.21&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          422.5655&nbsp;&nbsp;</td>
 <td class="fix">0.0009&nbsp;&nbsp;</td>
 <td class="fix">          422.56552&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.82e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000008" class="en_span" onclick="selectById('087001.000008')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">23 658.306&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;8<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          432.5361&nbsp;&nbsp;</td>
 <td class="fix">0.0009&nbsp;&nbsp;</td>
 <td class="fix">          432.53607&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.64e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000007" class="en_span" onclick="selectById('087001.000007')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">23 112.960&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;8<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          494.6157&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          494.61573&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.67e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000074" class="en_span" onclick="selectById('087001.000074')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 449.483&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          495.9144&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          495.91444&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.00e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000069" class="en_span" onclick="selectById('087001.000069')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 396.552&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          496.9031&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          496.90308&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.57e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000066" class="en_span" onclick="selectById('087001.000066')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 356.444&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          497.4988&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          497.49878&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.39e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000064" class="en_span" onclick="selectById('087001.000064')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 332.354&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          498.7192&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          498.71920&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">5.59e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000061" class="en_span" onclick="selectById('087001.000061')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 283.180&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          499.4600&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          499.45999&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.98e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000059" class="en_span" onclick="selectById('087001.000059')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 253.449&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          500.9918&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          500.99176&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.97e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000056" class="en_span" onclick="selectById('087001.000056')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 192.251&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          501.9293&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          501.92929&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.81e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000054" class="en_span" onclick="selectById('087001.000054')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 154.979&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          503.8896&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          503.88964&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">8.88e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000051" class="en_span" onclick="selectById('087001.000051')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 077.492&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          505.1011&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          505.10105&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.84e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000049" class="en_span" onclick="selectById('087001.000049')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 029.909&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          507.6691&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          507.66912&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.15e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000046" class="en_span" onclick="selectById('087001.000046')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 929.789&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          509.2753&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          509.27532&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.28e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000044" class="en_span" onclick="selectById('087001.000044')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 867.682&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          512.7362&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          512.73622&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.56e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000041" class="en_span" onclick="selectById('087001.000041')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 735.182&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          514.9331&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          514.93307&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">8.48e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000039" class="en_span" onclick="selectById('087001.000039')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 652.000&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          519.7664&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          519.76640&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.14e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000036" class="en_span" onclick="selectById('087001.000036')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 471.465&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          522.8917&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          522.89168&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.19e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000034" class="en_span" onclick="selectById('087001.000034')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 356.506&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          529.9592&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          529.95916&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.05e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000031" class="en_span" onclick="selectById('087001.000031')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 101.539&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          534.6417&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          534.64166&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.76e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000029" class="en_span" onclick="selectById('087001.000029')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 936.325&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          539.6176&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          539.61762&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.55e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000075" class="en_span" onclick="selectById('087001.000075')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 450.488&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          539.6469&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          539.64690&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.62e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000074" class="en_span" onclick="selectById('087001.000074')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 449.483&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          541.1578&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          541.15779&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.86e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000070" class="en_span" onclick="selectById('087001.000070')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 397.761&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          541.1932&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          541.19321&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.13e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000069" class="en_span" onclick="selectById('087001.000069')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 396.552&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          542.3708&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          542.37083&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.99e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000066" class="en_span" onclick="selectById('087001.000066')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 356.444&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          543.0372&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          543.03716&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.66e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000065" class="en_span" onclick="selectById('087001.000065')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 333.827&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          543.0806&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          543.08061&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.73e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000064" class="en_span" onclick="selectById('087001.000064')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 332.354&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          544.5352&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          544.53524&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">8.73e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000061" class="en_span" onclick="selectById('087001.000061')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 283.180&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          545.3642&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          545.36417&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.77e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000060" class="en_span" onclick="selectById('087001.000060')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 255.275&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          545.4185&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          545.41850&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.55e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000059" class="en_span" onclick="selectById('087001.000059')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 253.449&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          545.6375&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          545.63748&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.56e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000026" class="en_span" onclick="selectById('087001.000026')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 559.504&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          547.2457&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          547.24565&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.06e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000056" class="en_span" onclick="selectById('087001.000056')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 192.251&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          548.2954&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          548.29545&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.45e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000055" class="en_span" onclick="selectById('087001.000055')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 157.274&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          548.3645&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          548.36447&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">5.80e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000054" class="en_span" onclick="selectById('087001.000054')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 154.979&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          550.7052&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          550.70515&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.35e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000051" class="en_span" onclick="selectById('087001.000051')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 077.492&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          552.0637&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          552.06365&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.48e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000050" class="en_span" onclick="selectById('087001.000050')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 032.821&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          552.1524&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          552.15244&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.37e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000049" class="en_span" onclick="selectById('087001.000049')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 029.909&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          553.1716&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          553.17161&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.67e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000024" class="en_span" onclick="selectById('087001.000024')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 309.962&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          555.2227&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          555.22268&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.75e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000046" class="en_span" onclick="selectById('087001.000046')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 929.789&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          557.0255&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          557.02549&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.74e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000045" class="en_span" onclick="selectById('087001.000045')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 871.514&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          557.1444&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          557.14445&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.77e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000044" class="en_span" onclick="selectById('087001.000044')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 867.682&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          561.2892&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          561.28917&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.37e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000041" class="en_span" onclick="selectById('087001.000041')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 735.182&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          563.7589&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          563.75890&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.35e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000040" class="en_span" onclick="selectById('087001.000040')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 657.155&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          563.9228&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          563.92284&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.32e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000039" class="en_span" onclick="selectById('087001.000039')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 652.000&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          569.7247&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          569.72475&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.25e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000036" class="en_span" onclick="selectById('087001.000036')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 471.465&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          571.8746&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          571.87464&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.38e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000021" class="en_span" onclick="selectById('087001.000021')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 718.909&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          573.2468&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          573.24676&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.09e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000035" class="en_span" onclick="selectById('087001.000035')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 363.655&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          573.4818&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          573.48185&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.80e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000034" class="en_span" onclick="selectById('087001.000034')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 356.506&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          585.3491&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          585.34910&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.44e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000019" class="en_span" onclick="selectById('087001.000019')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 316.497&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          587.2900&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          587.29000&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.58e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000030" class="en_span" onclick="selectById('087001.000030')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 946.643&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          587.6462&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          587.64619&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.65e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000029" class="en_span" onclick="selectById('087001.000029')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 936.325&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          600.9574&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          600.95745&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.84e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000026" class="en_span" onclick="selectById('087001.000026')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 559.504&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          609.5276&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          609.52762&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.56e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000025" class="en_span" onclick="selectById('087001.000025')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 325.605&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          610.1095&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          610.10952&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.99e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000024" class="en_span" onclick="selectById('087001.000024')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 309.962&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          618.560&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          618.5596&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.93e+02&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000068" class="en_span" onclick="selectById('087001.000068')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 391.99&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          618.783&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          618.7831&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.94e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000067" class="en_span" onclick="selectById('087001.000067')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 386.15&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          621.066&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          621.0658&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.90e+02&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000063" class="en_span" onclick="selectById('087001.000063')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 326.77&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          621.341&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          621.3414&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.91e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000062" class="en_span" onclick="selectById('087001.000062')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 319.63&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          621.9813&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          621.98126&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.33e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000016" class="en_span" onclick="selectById('087001.000016')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">28 310.617&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          624.178&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          624.1780&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.23e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000058" class="en_span" onclick="selectById('087001.000058')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 246.51&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          624.524&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          624.5235&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.21e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000057" class="en_span" onclick="selectById('087001.000057')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 237.65&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          626.301&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          626.3009&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.89e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000068" class="en_span" onclick="selectById('087001.000068')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 391.99&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          628.112&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          628.1118&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.53e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000053" class="en_span" onclick="selectById('087001.000053')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 146.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          628.553&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          628.5529&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.54e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000052" class="en_span" onclick="selectById('087001.000052')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 135.03&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          628.870&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          628.8704&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">8.60e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000063" class="en_span" onclick="selectById('087001.000063')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 326.77&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          632.062&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          632.0616&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.05e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000058" class="en_span" onclick="selectById('087001.000058')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 246.51&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          632.9403&nbsp;&nbsp;</td>
 <td class="fix">0.0003&nbsp;&nbsp;</td>
 <td class="fix">          632.9403&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.10e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000021" class="en_span" onclick="selectById('087001.000021')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 718.909&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          633.190&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          633.1901&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.99e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000048" class="en_span" onclick="selectById('087001.000048')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 018.55&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          633.766&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          633.7661&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.99e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000047" class="en_span" onclick="selectById('087001.000047')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 004.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          636.096&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          636.0957&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.33e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000053" class="en_span" onclick="selectById('087001.000053')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 146.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          639.914&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          639.9141&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.63e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000043" class="en_span" onclick="selectById('087001.000043')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 852.65&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          640.689&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          640.6887&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.63e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000042" class="en_span" onclick="selectById('087001.000042')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 833.76&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          641.304&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          641.3044&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.73e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000048" class="en_span" onclick="selectById('087001.000048')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 018.55&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          648.203&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          648.2027&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.28e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000043" class="en_span" onclick="selectById('087001.000043')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 852.65&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          648.4210&nbsp;&nbsp;</td>
 <td class="fix">0.0003&nbsp;&nbsp;</td>
 <td class="fix">          648.4210&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.91e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000020" class="en_span" onclick="selectById('087001.000020')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 341.817&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          649.103&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          649.1030&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.61e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000038" class="en_span" onclick="selectById('087001.000038')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 631.49&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          649.4876&nbsp;&nbsp;</td>
 <td class="fix">0.0003&nbsp;&nbsp;</td>
 <td class="fix">          649.4876&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.41e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000019" class="en_span" onclick="selectById('087001.000019')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 316.497&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          650.181&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          650.1812&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.52e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000037" class="en_span" onclick="selectById('087001.000037')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 605.95&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          650.7242&nbsp;&nbsp;</td>
 <td class="fix">0.0003&nbsp;&nbsp;</td>
 <td class="fix">          650.7242&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">8.04e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000014" class="en_span" onclick="selectById('087001.000014')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 600.657&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;8<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          657.633&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          657.633&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.06e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000038" class="en_span" onclick="selectById('087001.000038')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 631.49&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          662.174&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          662.174&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">5.01e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000033" class="en_span" onclick="selectById('087001.000033')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 327.46&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          663.746&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          663.746&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">5.00e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000032" class="en_span" onclick="selectById('087001.000032')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 291.72&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          671.054&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          671.054&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.35e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000033" class="en_span" onclick="selectById('087001.000033')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 327.46&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          681.787&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          681.787&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.49e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000028" class="en_span" onclick="selectById('087001.000028')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 893.16&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          684.222&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          684.222&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.46e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000027" class="en_span" onclick="selectById('087001.000027')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 840.98&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          691.204&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          691.204&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.50e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000028" class="en_span" onclick="selectById('087001.000028')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 893.16&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          694.8987&nbsp;&nbsp;</td>
 <td class="fix">0.0003&nbsp;&nbsp;</td>
 <td class="fix">          694.8987&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.90e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000016" class="en_span" onclick="selectById('087001.000016')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">28 310.617&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          713.491&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          713.491&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.16e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000023" class="en_span" onclick="selectById('087001.000023')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 241.60&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          717.616&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          717.615&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.15e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000022" class="en_span" onclick="selectById('087001.000022')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 161.07&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          717.98660&nbsp;</td>
 <td class="fix">0.00010&nbsp;</td>
 <td class="fix">          717.98664&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.78e+07&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          723.811&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          723.811&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.01e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000023" class="en_span" onclick="selectById('087001.000023')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 241.60&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          728.5892&nbsp;&nbsp;</td>
 <td class="fix">0.0004&nbsp;&nbsp;</td>
 <td class="fix">          728.5892&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.93e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000015" class="en_span" onclick="selectById('087001.000015')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 645.373&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;8<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          730.9713&nbsp;&nbsp;</td>
 <td class="fix">0.0004&nbsp;&nbsp;</td>
 <td class="fix">          730.9713&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.13e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000014" class="en_span" onclick="selectById('087001.000014')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 600.657&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;8<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          744.198&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          744.1976&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.75e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000011" class="en_span" onclick="selectById('087001.000011')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">25 671.00&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          770.904&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          770.904&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.99e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000018" class="en_span" onclick="selectById('087001.000018')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 198.09&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          778.947&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          778.947&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.95e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000017" class="en_span" onclick="selectById('087001.000017')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 064.18&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          782.965&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          782.965&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.72e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000018" class="en_span" onclick="selectById('087001.000018')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">29 198.09&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;10<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          790.171&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          790.171&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.55e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000068" class="en_span" onclick="selectById('087001.000068')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 391.99&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          790.536&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          790.536&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">6.56e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000067" class="en_span" onclick="selectById('087001.000067')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 386.15&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;20<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          794.265&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          794.265&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.98e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000063" class="en_span" onclick="selectById('087001.000063')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 326.77&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          794.716&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          794.716&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.99e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000062" class="en_span" onclick="selectById('087001.000062')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 319.63&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;19<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          799.362&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          799.362&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.92e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000058" class="en_span" onclick="selectById('087001.000058')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 246.51&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          799.929&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          799.929&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">9.93e+03&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000057" class="en_span" onclick="selectById('087001.000057')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 237.65&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;18<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          805.826&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          805.826&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.29e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000053" class="en_span" onclick="selectById('087001.000053')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 146.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          806.552&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          806.552&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.29e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000052" class="en_span" onclick="selectById('087001.000052')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 135.03&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;17<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          814.203&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          814.203&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.70e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000048" class="en_span" onclick="selectById('087001.000048')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 018.55&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          815.156&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          815.156&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.66e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000047" class="en_span" onclick="selectById('087001.000047')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">32 004.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;16<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          816.9418&nbsp;&nbsp;</td>
 <td class="fix">0.0002&nbsp;&nbsp;</td>
 <td class="fix">          816.94184&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.22e+07&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix">  <span id="087001.000001" class="en_span" onclick="selectById('087001.000001')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">0.000&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          825.355&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          825.355&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.28e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000043" class="en_span" onclick="selectById('087001.000043')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 852.65&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          826.644&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          826.644&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.28e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000042" class="en_span" onclick="selectById('087001.000042')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 833.76&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;15<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          832.645&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.002&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          832.6449&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.66e+07&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000002" class="en_span" onclick="selectById('087001.000002')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">12 237.409&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000009" class="en_span" onclick="selectById('087001.000009')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">24 244.03&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;7<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          840.705&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          840.705&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.18e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000038" class="en_span" onclick="selectById('087001.000038')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 631.49&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          842.515&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          842.515&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.17e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000037" class="en_span" onclick="selectById('087001.000037')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 605.95&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;14<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          851.047&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          851.047&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.66e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000011" class="en_span" onclick="selectById('087001.000011')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">25 671.00&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          862.763&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          862.763&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.68e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000033" class="en_span" onclick="selectById('087001.000033')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 327.46&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          865.433&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.004&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          865.433&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">4.66e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000032" class="en_span" onclick="selectById('087001.000032')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">31 291.72&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;13<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='odd'>

 <td class="fix">          896.359&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.005&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          896.359&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.53e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000028" class="en_span" onclick="selectById('087001.000028')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 893.16&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          897.715&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.005&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          897.715&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.85e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000013" class="en_span" onclick="selectById('087001.000013')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 366.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          900.572&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.005&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          900.573&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">7.31e+04&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000027" class="en_span" onclick="selectById('087001.000027')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 840.98&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;12<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          914.113&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.005&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          914.113&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.31e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000005" class="en_span" onclick="selectById('087001.000005')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 429.64&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000013" class="en_span" onclick="selectById('087001.000013')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 366.20&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='odd'>

 <td class="fix">          918.162&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.005&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          918.162&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">3.61e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000004" class="en_span" onclick="selectById('087001.000004')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">16 229.87&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000012" class="en_span" onclick="selectById('087001.000012')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">27 118.21&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;6<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;9<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

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
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr class='evn'>

 <td class="fix">          951.973&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.005&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          951.973&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.30e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000023" class="en_span" onclick="selectById('087001.000023')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 241.60&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          959.329&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.005&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          959.329&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.29e+05&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000006" class="en_span" onclick="selectById('087001.000006')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">19 739.98&nbsp;&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000022" class="en_span" onclick="selectById('087001.000022')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">30 161.07&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;8<i>s</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>S&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;11<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>1</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          960.450&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          960.450&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">1.29e+07&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000010" class="en_span" onclick="selectById('087001.000010')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">24 332.93&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;7<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>5</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>


<tr class='evn'>

 <td class="fix">          968.724&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">0.003&nbsp;&nbsp;&nbsp;</td>
 <td class="fix">          968.724&nbsp;&nbsp;&nbsp;</td>
 <td class="cnt">&nbsp;&nbsp;</td>
 <td class="lft1">2.09e+06&nbsp;</td>
 <td class="lft1">&nbsp;&nbsp;</td>
 <td class="fix"><span id="087001.000003" class="en_span" onclick="selectById('087001.000003')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">13 923.998&nbsp;</span></td>
 <td class="dsh">-&nbsp;</td>
 <td class="fix"><span id="087001.000009" class="en_span" onclick="selectById('087001.000009')" onmouseover="setMOn(this)" onmouseout="setMOff(this)">24 244.03&nbsp;&nbsp;</span></td>
 <td class="lft1">&nbsp;7<i>p</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>P&deg;&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="lft1">&nbsp;7<i>d</i>&nbsp;</td>
 <td class="lft1">&nbsp;<sup>2</sup>D&nbsp;</td>
 <td class="lft1">&nbsp;<sup>3</sup>/<sub>2</sub>&nbsp;</td>
 <td class="cnt"><sup></sup><a class='bal' href="javascript:void(toggleBalloon(''))" onmouseover="showBalloon('',event);self.status='Help';return true" onmouseout="hideBalloon('',event);self.status='';return true"></a><sub></sub></td>
 <td class="bib"><a class='bib' onmouseover="self.status='TP Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=tp&amp;db_id=6898&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=6898&amp;type=');return false">T6898</a></td>
 <td class="bib"><a class='bib' onmouseover="self.status='Line Bibliographic Reference for Fr I'; return true;" onmouseout="self.status='';return true;" target="_blank"  href="javascript:void(0)" onclick="popded('https://physics.nist.gov/cgi-bin/ASBib1/get_ASBib_ref.cgi?db=el&amp;db_id=12264&amp;comment_code=&amp;element=Fr&amp;spectr_charge=1&amp;ref=12264&amp;type=');return false">L12264</a></td>

</tr>

</tbody>
    </body>
</html>
HTML;

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
     * @throws \RuntimeException
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
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $elements = [
//            'H',
//            'He',
//            'Np',
//            'Fr',
//            'At',
            'Cf',
        ];

        $progressBar = new ProgressBar($output, \count($elements));
        $progressBar->start();

        foreach ($elements as $element) {
            $url_1 = 'https://physics.nist.gov/cgi-bin/ASD/lines_hold.pl?el=' . $element;
            $referrer = 'https://physics.nist.gov/cgi-bin/ASD/lines_pt.pl';
            $html = $this->htmlIons;
//            $html = $this->httpRequest($url_1, $referrer);
            $crawler = new Crawler($html);
            $rows = $this->parseIons($crawler);

            $tableIons = new Table($output);
            $tableIons->setHeaders([
                    'Ion',
                    'No. of lines',
                    'Lines with transition probabilities',
                    'Lines with level designations'
                ])->setRows($rows);
            $tableIons->render();

            foreach ($rows as $row) {

                [$rows, $headers] = $this->parseSpectra($url_1, $row[0]);

                $table = new Table($output);
                $table->setHeaders($headers)
                    ->setRows($rows);
                $table->render();
            }
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
    protected function parseSpectra(string $referrer, string $spectra): array
    {
        $url = 'https://physics.nist.gov/cgi-bin/ASD/lines1.pl?unit=1&line_out=0&bibrefs=1&show_obs_wl=1&show_calc_wl=1&A_out=0&intens_out=1&allowed_out=1&forbid_out=1&conf_out=1&term_out=1&enrg_out=1&J_out=1&g_out=0&spectra=' . rawurlencode($spectra);
        $html2 = $this->htmlSpectra;
//        $html2 = $this->httpRequest($url, $referrer);
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
        $headers = $crawler->filter('tr  > th')->each(function (Crawler $node, $i) {
            $trimmed = trim(trim(strip_tags($node->text())), \chr(0xC2) . \chr(0xA0));
            return \substr_count($trimmed, ', ') ? explode(', ', $trimmed) : [$trimmed];
        });
        return array_merge(...$headers);
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
        $oddRows = array_chunk($crawler->filter('tr.odd  > td')->each(function (Crawler $node, $i) {
            return trim(trim($node->text()), \chr(0xC2) . \chr(0xA0));
        }), \count($headers));
        $envRows = array_chunk($crawler->filter('tr.evn  > td')->each(function (Crawler $node, $i) {
            return trim(trim($node->text()), \chr(0xC2) . \chr(0xA0));
        }), \count($headers));
        return array_merge($oddRows, $envRows);
    }
}
