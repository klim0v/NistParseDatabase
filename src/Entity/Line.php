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
 * @ORM\Table(name="line")
 */
class Line
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $observedWavelength;

    /**
     * @ORM\Column(type="float")
     */
    private $ritzWavelength;

    /**
     * @ORM\Column(type="float")
     */
    private $unc;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isVac;

    /**
     * @var Ion
     *
     * @ORM\ManyToOne(targetEntity="Ion", inversedBy="lines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ion;

    public function getIon(): ?Ion
    {
        return $this->ion;
    }

    public function setIon(?Ion $ion): void
    {
        $this->ion = $ion;
    }
}
