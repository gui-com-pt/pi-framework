<?hh

namespace Pi\ServiceModel;

use Pi\Odm\Interfaces\IEntity;

class PostArticleCategoryRequest implements IEntity {

  protected $displayName;

  protected $parent;

  <<String>>
  public function getDisplayName()
  {
    return $this->displayName;
  }

  public function setDisplayName(string $value)
  {
    $this->displayName = $value;
  }

  <<String>>
  public function getParent()
  {
    return $this->parent;
  }

  public function setParent($parent)
  {
    $this->parent = $parent;
  }
}
