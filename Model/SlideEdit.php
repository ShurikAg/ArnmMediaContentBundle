<?php
namespace Arnm\MediaContentBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Model a single slide editing
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class SlideEdit
{

    /**
     * @Assert\Image()
     */
    public $media;

    /**
     * Title of the slide. Might be used for anything...
     *
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\Length(
     * min=1,
     * minMessage="Slug must be at least {{ limit }} characters."
     * )
     */
    private $title;

    /**
     * @var string $url
     *
     *
     * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\Length(
     * min=1,
     * minMessage="Slug must be at least {{ limit }} characters."
     * )
     */
    private $url;

    /**
     * Order of the slide
     *
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Range(min = "1")
     * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
     */
    private $order;

    /**
     * @return UploadedFile
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Sets URL
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    /**
     * Gets URL value
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets order of the slide
     *
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = (int) $order;
    }

    /**
     * Gets an order of the slide
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets title for the slide
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Gets the title of the slide
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
