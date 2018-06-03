<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 23.04.18
 * Time: 23:50
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="spectrum")
 */
class Spectrum
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $observedWavelength;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ritzWavelength;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vac;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $relInt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $unc;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $aki;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $acc;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $ei;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $ek;

//    /**
//     * @ORM\Column(type="string", nullable=true)
//     */
//    private $term;
//      todo исправить объединение этих полей
//    /**
//     * @ORM\Column(type="string", nullable=true)
//     */
//    private $upperLevelConf;
//
//    /**
//     * @ORM\Column(type="string", nullable=true)
//     */
//    private $lowerLevelConf;

//    /**
//     * @ORM\Column(type="string", nullable=true)
//     */
//    private $j;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $tPRef;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lineRef;
//
//    /**
//     * @ORM\Column(type="json_array", nullable=true)
//     */
//    private $other;

    /**
     * @var Ion
     *
     * @ORM\ManyToOne(targetEntity="Ion", inversedBy="spectra", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $ion;


    public function __construct(array $combined)
    {
//        $this->other = [];
        /*var_export($combined);
        array (
            'Observed  Wavelength  Air (nm)' => '244.79058',
            'Unc.  (nm)' => '0.0000008',
            'Ritz  Wavelength  Air (nm)' => '244.79058',
            'Rel.  Int. (?)' => '1100',
            'Aki (s-1)' => '1.11e+07',
            'Acc.' => '',
            'Ei  (cm-1)' => '0.000',
            '' => '-',
            'Ek  (cm-1)' => '40 838.874',
            'Lower Level  Conf.' => '4d10',
            'Term' => '2[1/2]°',
            'J' => '1',
            'Upper Level  Conf.' => '4d9(2D3/2)5p',
            'Type' => '',
            'TPRef.' => 'T7227',
            'LineRef.' => 'L9430',
        );
        die;*/
        foreach ($combined as $key => $value) {
            switch ($key) {
                case 'Observed  Wavelength  Vac (nm)':
                    $this->setObservedWavelength($value);
                    $this->setVac(true);
                    break;
                case 'Observed  Wavelength  Air (nm)':
                    $this->setObservedWavelength($value);
                    $this->setVac(false);
                    break;
                case 'Ritz  Wavelength  Vac (nm)':
                    $this->setRitzWavelength($value);
                    $this->setVac(true);
                    break;
                case 'Ritz  Wavelength  Air (nm)':
                    $this->setRitzWavelength($value);
                    $this->setVac(false);
                    break;
                case 'Rel.  Int. (?)':
                    $this->setRelInt($value);
                    break;
                case 'Unc.  (nm)':
                    $this->setUnc($value);
                    break;
                case 'Aki (s-1)':
                    $this->setAki($value);
                    break;
                case 'Acc.':
                    $this->setAcc($value);
                    break;
                case 'Ei  (cm-1)':
                    $this->setEi($value);
                    break;
                case 'Ek  (cm-1)':
                    $this->setEk($value);
                    break;
                /*case 'Lower Level  Conf.':
                    $this->setLowerLevelConf($value);
                    break;
                case 'Term':
                    $this->setTerm($value);
                    break;
                case 'J':
                    $this->setJ($value);
                    break;
                case 'Upper Level  Conf.':
                    $this->setUpperLevelConf($value);
                    break;*/
                case 'Type':
                    $this->setType($value);
                    break;
                case 'TPRef.':
                    $this->setTPRef($value);
                    break;
                case 'LineRef.':
                    $this->setLineRef($value);
                    break;
                case '':
                    break;
                default:
//                    $this->addOther([$key => $value]);
                    break;
            }
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIon(): ?Ion
    {
        return $this->ion;
    }

    public function setIon(?Ion $ion): void
    {
        $this->ion = $ion;
    }

    public function getVac(): bool
    {
        return $this->vac;
    }

    public function setVac(bool $vac): void
    {
        $this->vac = $vac;
    }

    public function getObservedWavelength()
    {
        return $this->observedWavelength;
    }

    public function setObservedWavelength($observedWavelength): void
    {
        $this->observedWavelength = $observedWavelength;
    }

    public function getRitzWavelength()
    {
        return $this->ritzWavelength;
    }

    public function setRitzWavelength($ritzWavelength): void
    {
        $this->ritzWavelength = $ritzWavelength;
    }

    /**
     * @return mixed
     */
    public function getRelInt()
    {
        return $this->relInt;
    }

    /**
     * @param mixed $relInt
     */
    public function setRelInt($relInt): void
    {
        $this->relInt = $relInt;
    }

    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param mixed $term
     */
    public function setTerm($term): void
    {
        $this->term = $term;
    }

    /**
     * @return mixed
     */
    public function getUpperLevelConf()
    {
        return $this->upperLevelConf;
    }

    /**
     * @param mixed $upperLevelConf
     */
    public function setUpperLevelConf($upperLevelConf): void
    {
        $this->upperLevelConf = $upperLevelConf;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getLowerLevelConf()
    {
        return $this->lowerLevelConf;
    }

    /**
     * @param mixed $lowerLevelConf
     */
    public function setLowerLevelConf($lowerLevelConf): void
    {
        $this->lowerLevelConf = $lowerLevelConf;
    }

    /**
     * @return mixed
     */
    public function getAki()
    {
        return $this->aki;
    }

    /**
     * @param mixed $aki
     */
    public function setAki($aki): void
    {
        $this->aki = $aki;
    }

    /**
     * @return mixed
     */
    public function getEi()
    {
        return $this->ei;
    }

    /**
     * @param mixed $ei
     */
    public function setEi($ei): void
    {
        $this->ei = $ei;
    }

    /**
     * @return mixed
     */
    public function getEk()
    {
        return $this->ek;
    }

    /**
     * @param mixed $ek
     */
    public function setEk($ek): void
    {
        $this->ek = $ek;
    }

    /**
     * @return mixed
     */
    public function getJ()
    {
        return $this->j;
    }

    /**
     * @param mixed $j
     */
    public function setJ($j): void
    {
        $this->j = $j;
    }

    /**
     * @return mixed
     */
    public function getTPRef()
    {
        return $this->tPRef;
    }

    /**
     * @param mixed $tPRef
     */
    public function setTPRef($tPRef): void
    {
        $this->tPRef = $tPRef;
    }

    /**
     * @return mixed
     */
    public function getLineRef()
    {
        return $this->lineRef;
    }

    /**
     * @param mixed $lineRef
     */
    public function setLineRef($lineRef): void
    {
        $this->lineRef = $lineRef;
    }

    /**
     * @return mixed
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * @param mixed $acc
     */
    public function setAcc($acc): void
    {
        $this->acc = $acc;
    }

    /**
     * @return mixed
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * @param mixed $other
     */
    public function addOther($other): void
    {
        $this->other[] = $other;
    }

    /**
     * @return mixed
     */
    public function getUnc()
    {
        return $this->unc;
    }

    /**
     * @param mixed $unc
     */
    public function setUnc($unc): void
    {
        $this->unc = $unc;
    }
}
