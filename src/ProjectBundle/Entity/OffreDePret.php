<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;


/**
 * OffreDePret
 *
 * @ORM\Table(name="offre_de_pret")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\OffreDePretRepository")
 */
class OffreDePret
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
     * @ORM\ManyToOne(targetEntity="ProjectBundle\Entity\Project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lender;

    /**
     * @var int
     *
     * @ORM\Column(name="Somme", type="integer")
     */
    private $somme;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebutRemboursement", type="date")
     * @Assert\Date()
     * @Assert\GreaterThan("+7 days")
     */
    private $dateDebutRemboursement;

    /**
     * @var int
     *
     * @ORM\Column(name="echeance", type="integer")
     */
    private $echeance;

    /**
     * @var int
     *
     * @ORM\Column(name="interets", type="integer")
     */
    private $interets;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $needToAccept;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePret", type="date", nullable=true)
     * @Assert\DateTime()
     */
    private $datePret;

    /**
     * @ORM\Column(name="contrat", type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $contrat;


    public function __construct()
    {

       }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set project
     *
     * @param Project $project
     *
     * @return OffreDePret
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set lender
     *
     * @param User $lender
     *
     * @return OffreDePret
     */
    public function setLender(User $lender)
    {
        $this->lender = $lender;

        return $this;
    }

    /**
     * Get lender
     *
     * @return User
     */
    public function getLender()
    {
        return $this->lender;
    }

    /**
     * Set somme
     *
     * @param integer $somme
     *
     * @return OffreDePret
     */
    public function setSomme($somme)
    {
        $this->somme = $somme;

        return $this;
    }

    /**
     * Get somme
     *
     * @return int
     */
    public function getSomme()
    {
        return $this->somme;
    }

    /**
     * Set dateDebutRemboursement
     *
     * @param \DateTime $dateDebutRemboursement
     *
     * @return OffreDePret
     */
    public function setDateDebutRemboursement($dateDebutRemboursement)
    {
        $this->dateDebutRemboursement = $dateDebutRemboursement;

        return $this;
    }

    /**
     * Get dateDebutRemboursement
     *
     * @return \DateTime
     */
    public function getDateDebutRemboursement()
    {
        return $this->dateDebutRemboursement;
    }

    /**
     * Set echeance
     *
     * @param integer $echeance
     *
     * @return OffreDePret
     */
    public function setEcheance($echeance)
    {
        $this->echeance = $echeance;

        return $this;
    }

    /**
     * Get echeance
     *
     * @return int
     */
    public function getEcheance()
    {
        return $this->echeance;
    }

    /**
     * Set interets
     *
     * @param integer $interets
     *
     * @return OffreDePret
     */
    public function setInterets($interets)
    {
        $this->interets = $interets;

        return $this;
    }

    /**
     * Get interets
     *
     * @return int
     */
    public function getInterets()
    {
        return $this->interets;
    }

    /**
     * Set datePret
     *
     * @param \dateTime $datePret
     *
     * @return OffreDePret
     */
    public function setDatePret(\dateTime $datePret)
    {
        $this->datePret = $datePret;

        return $this;
    }

    /**
     * Get datePret
     *
     * @return \dateTime
     */
    public function getDatePret()
    {
        return $this->datePret;
    }

    /**
     * Set needToAccept
     *
     * @param \AppBundle\Entity\User $needToAccept
     *
     * @return OffreDePret
     */
    public function setNeedToAccept(\AppBundle\Entity\User $needToAccept = null)
    {
        $this->needToAccept = $needToAccept;

        return $this;
    }

    /**
     * Get needToAccept
     *
     * @return \AppBundle\Entity\User
     */
    public function getNeedToAccept()
    {
        return $this->needToAccept;
    }
}
