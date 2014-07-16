<?php
namespace Arnm\MediaContentBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Model a single filmstrip item management
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class StripItem
{

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
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
     * Title of the item. Might be used for anything...
     *
     * @var string
     *
     * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
     * @Assert\Length(
     * min=1,
     * minMessage="Slug must be at least {{ limit }} characters."
     * )
     */
    private $caption;

    /**
     * Optional target url of the item
     *
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
     * Order of the item
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
     * Sets order of the item
     *
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = (int) $order;
    }

    /**
     * Gets an order of the item
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets title for the item
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Gets the title of the item
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets caption for the item
     *
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = (string) $caption;
    }

    /**
     * Gets the caption of the item
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }
}
