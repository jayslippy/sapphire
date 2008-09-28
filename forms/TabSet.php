<?php
/**
 * Defines a set of tabs in a form.
 * The tabs are build with our standard tabstrip javascript library.  By default, the HTML is
 * generated using FieldHolder.
 * @package forms
 * @subpackage fields-structural
 */
class TabSet extends CompositeField {
	public function __construct($id) {
		$tabs = func_get_args();
		$this->id = array_shift($tabs);
		$this->name = $this->id;
		$this->title = $this->id;
		
		foreach($tabs as $tab) $tab->setTabSet($this);
		
		parent::__construct($tabs);
	}
	
	public function id() {
		if($this->tabSet) return $this->tabSet->id() . '_' . $this->id . '_set';
		else return $this->id;
	}
	
	/**
	 * Returns a tab-strip and the associated tabs.
	 * The HTML is a standardised format, containing a &lt;ul;
	 */
	public function FieldHolder() {
		Requirements::javascript(THIRDPARTY_DIR . "/loader.js");
		Requirements::javascript(THIRDPARTY_DIR . "/prototype.js");
		Requirements::javascript(THIRDPARTY_DIR . "/behaviour.js");
		Requirements::javascript(THIRDPARTY_DIR . "/prototype_improvements.js");
		Requirements::javascript(THIRDPARTY_DIR . "/tabstrip/tabstrip.js");
		Requirements::css(THIRDPARTY_DIR . "/tabstrip/tabstrip.css");
		
		return $this->renderWith("TabSetFieldHolder");
	}
	
	/**
	 * Return a dataobject set of all this classes tabs
	 */
	public function Tabs() {
		return $this->children;
	}
	public function setTabs($children){
		$this->children = $children;
	}

	public function setTabSet($val) {
		$this->tabSet = $val;
	}
	public function getTabSet() {
		if(isset($this->tabSet)) return $this->tabSet;
	}
	
	/**
	 * Returns the named tab
	 */
	public function fieldByName($name) {
		foreach($this->children as $child) {
			if($name == $child->Name || $name == $child->id) return $child;
		}
	}

	/**
	 * Add a new child field to the end of the set.
	 */
	public function push($field) {
		parent::push($field);
		$field->setTabSet($this);
	}
	public function insertBefore($field, $insertBefore) {
		parent::insertBefore($field, $insertBefore);
		$field->setTabSet($this);
	}
	
	public function insertBeforeRecursive($field, $insertBefore, $level) {
		$level = parent::insertBeforeRecursive($field, $insertBefore, $level+1);
		if ($level === 0) $field->setTabSet($this);
		return $level;
	}
	
	public function removeByName( $tabName, $dataFieldOnly = false ) {
		parent::removeByName( $tabName, $dataFieldOnly );
	}
}
?>
