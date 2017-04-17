<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jour
 *
 * @ORM\Table(name="jour")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\JourRepository")
 */
class Jour
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prgMatin", type="string", length=1000, nullable=true)
     */
    private $prgMatin;

    /**
     * @var string
     *
     * @ORM\Column(name="progMedi", type="string", length=1000, nullable=true)
     */
    private $progMedi;

    /**
     * @ORM\ManyToOne(targetEntity="Formation",inversedBy="formations",cascade={"persist"})
     * @ORM\JoinColumn(name="jour_id" ,referencedColumnName="id",nullable=true, onDelete="SET NULL")
     */


    private $jour;





    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prgMatin
     *
     * @param string $prgMatin
     * @return Jour
     */
    public function setPrgMatin($prgMatin)
    {
        $this->prgMatin = $prgMatin;

        return $this;
    }

    /**
     * Get prgMatin
     *
     * @return string 
     */
    public function getPrgMatin()
    {
        return $this->prgMatin;
    }

    /**
     * Set progMedi
     *
     * @param string $progMedi
     * @return Jour
     */
    public function setProgMedi($progMedi)
    {
        $this->progMedi = $progMedi;

        return $this;
    }

    /**
     * Get progMedi
     *
     * @return string 
     */
    public function getProgMedi()
    {
        return $this->progMedi;
    }

    /**
     * Set jour
     *
     * @param \GestionBundle\Entity\Formation $jour
     * @return Jour
     */
    public function setJour(\GestionBundle\Entity\Formation $jour = null)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return \GestionBundle\Entity\Formation 
     */
    public function getJour()
    {
        return $this->jour;
    }
}
