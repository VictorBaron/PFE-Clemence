<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;


/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\ProjectRepository")
 */
class Project
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min=5, minMessage="Le titre doit faire au moins {{ limit }} caractères.")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

   /**
   * @ORM\Column(name="published", type="boolean")
   */
    private $published =true;

    /**
    * @var int
     *
   * @ORM\Column(name="sommeDemandee", type="integer")
   * @Assert\Range(min=100, max=10000, minMessage="La somme demandée doit être supérieure à {{ limit }}.",  maxMessage="La somme demandée doit être inférieure à {{ limit }}.")
   */
    private $sommeDemandee;

    /**
    * @var int
     *
   * @ORM\Column(name="sommeRecue", type="integer")
   */
    private $sommeRecue;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     * @Assert\DateTime()
     * @Assert\Range(min="now", minMessage="La date butoire doit être supérieure à aujourd'hui.")
     */
    private $dateFin;


    public function __construct()
    {
        // Par défaut, la date de l'annonce est la date d'aujourd'hui
        $this->date = new \Datetime();
        $this->sommeDemandee =0;
        $this->dateFin = new \Datetime();
        $this->sommeRecue =0;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Project
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Project
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * Set content
     *
     * @param string $content
     *
     * @return Project
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Project
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set sommeDemandee
     *
     * @param integer $sommeDemandee
     *
     * @return Project
     */
    public function setSommeDemandee($sommeDemandee)
    {
        $this->sommeDemandee = $sommeDemandee;

        return $this;
    }

    /**
     * Get sommeDemandee
     *
     * @return integer
     */
    public function getSommeDemandee()
    {
        return $this->sommeDemandee;
    }

    /**
     * Set sommeRecue
     *
     * @param integer $sommeRecue
     *
     * @return Project
     */
    public function setSommeRecue($sommeRecue)
    {
        $this->sommeRecue = $sommeRecue;

        return $this;
    }

    /**
     * Get sommeRecue
     *
     * @return integer
     */
    public function getSommeRecue()
    {
        return $this->sommeRecue;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Project
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
}
