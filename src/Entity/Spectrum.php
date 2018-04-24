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
     * @var Ion
     *
     * @ORM\ManyToOne(targetEntity="Ion", inversedBy="spectra")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ion;


    public function __construct(array $combined)
    {
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
}
