<?php
/**
 * Haml elements list.
 *
 * @link http://haml.hamptoncatlin.com/ Original Sass parser (for Ruby)
 * @link http://phphaml.sourceforge.net/ Online documentation
 * @link http://sourceforge.net/projects/phphaml/ SourceForge project page
 * @license http://www.opensource.org/licenses/mit-license.php MIT (X11) License
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Haml
 */

require_once dirname(__FILE__) . '/../common/CommonElement.class.php';
require_once dirname(__FILE__) . '/HamlElementsList.class.php';

/**
 * Haml elements list.
 *
 * @link http://haml.hamptoncatlin.com/ Original Sass parser (for Ruby)
 * @link http://phphaml.sourceforge.net/ Online documentation
 * @link http://sourceforge.net/projects/phphaml/ SourceForge project page
 * @license http://www.opensource.org/licenses/mit-license.php MIT (X11) License
 * @author Amadeusz Jasak <amadeusz.jasak@gmail.com>
 * @package phpHaml
 * @subpackage Haml
 */
class HamlElement extends CommonElement
{
	/**
	 * The constructor
	 *
	 * @param HamlElement Parent element
	 * @param HamlElementsList Children
	 */
	public function __construct($parent = null, $children = null)
	{
		if ($parent instanceof HamlElement)
			$this->setParent($parent);
		if ($children instanceof HamlElementsList)
			$this->setChildren($children);
	}
	
	/**
	 * Is tag
	 *
	 * @var boolean
	 */
	protected $is_tag;
	
	/**
	 * Return $tag and can modify
	 * value.
	 *
	 * @param boolean Is tag
	 * @return boolean
	 */
	public function isTag($is_tag = null)
	{
		if (!is_null($is_tag))
			$this->is_tag = $is_tag;
		return $this->is_tag;
	}
	
	/**
	 * Tag name
	 *
	 * @var string
	 */
	protected $tag;
	
	/**
	 * Set tag.
	 *
	 * @param string Tag name
	 * @return object
	 */
	public function setTag($tag)
	{
		$this->tag = $tag;
		return $this;
	}
	
	/**
	 * Get tag.
	 *
	 * @return string Tag name
	 */
	public function getTag()
	{
		return $this->tag;
	}
	
	/**
	 * List of attributes
	 *
	 * @var array
	 */
	protected $attributes;
	
	/**
	 * Set attributes.
	 *
	 * @param array List of attributes
	 * @return object
	 */
	public function setAttributes(array $attributes)
	{
		$this->attributes = $attributes;
		return $this;
	}
	
	/**
	 * Get attributes.
	 *
	 * @return array List of attributes
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}
	
	/**
	 * Clear attributes.
	 *
	 * @return object
	 */
	public function clearAttributes()
	{
		$this->attributes->exchangeArray(array());
		return $this;
	}
	
	/**
	 * Check for value in attributes.
	 *
	 * @return boolean
	 */
	public function inAttributes($needle)
	{
		return in_array($needle, $this->attributes);
	}
	
	/**
	 * Element content
	 *
	 * @var string
	 */
	protected $content;
	
	/**
	 * Set content.
	 *
	 * @param string Element content
	 * @return object
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}
	
	/**
	 * Get content.
	 *
	 * @return string Element content
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 * Is content dynamic
	 *
	 * @var boolean
	 */
	protected $dynamic;
	
	/**
	 * Return $dynamic and can modify
	 * value.
	 *
	 * @param boolean Is content dynamic
	 * @return boolean
	 */
	public function isDynamic($dynamic = null)
	{
		if (!is_null($dynamic))
			$this->dynamic = $dynamic;
		return $this->dynamic;
	}
}

?>