<?hh

namespace Pi\ServiceModel;

use Pi\ServiceModel\Types\ItemAvailability,
	Pi\ServiceModel\Types\BusinessFunction,
	Pi\ServiceModel\Types\BusinessEntityType,
	Pi\ServiceModel\Types\OfferItemCondition;




/**
 * An offer to transfer some rights to an item or to provide a service—for
 * @example an offer to sell tickets to an event, to rent the DVD of a movie, to stream a TV show over the internet, to repair a motorcycle, or to loan a book.
 */
class PostOfferRequest {

	protected array $acceptedPaymentMethods;

	/**
	 * Additionals offers that can only be obtained in combination with the first base offer (e.g. supplements and extensions that are available for a surcharge).
	 */
	protected ?array $addons;

	/**
	 * The amount of time that is required between accepting the offer and the actual usage of the resource or service.
	 */
	protected $advanceBookingRequirement;

	/**
	 * The availability of this item—for example In stock, Out of stock, Pre-order, etc.
	 */
	protected ItemAvailability $availability;

	/**
	 * The end of the availability of the product or service included in the offer.
	 */
	protected ?\DateTime $availabilityEnds;

	/**
	 * The beginning of the availability of the product or service included in the offer.
	 */
	protected \DateTime $availabilityStarts;

	/**
	 * Place
	 */
	protected \MongoId $productId;

	protected array $availableDeliveryMethods;

	protected BusinessFunction $businessFunction;
	
	protected $category;

	/**
	 * The typical delay between the receipt of the order and the goods leaving the warehouse.
	 */
	protected $deliveryLeadTime;

	protected BusinessEntityType $eligibleCustomerType;

	/**
	 * A predefined value from OfferItemCondition or a textual description of the condition of the product or service, or the products or services included in the offer.
	 */
	protected OfferItemCondition $itemCondition;

	/**
	 * The item being offered.
	 */


	protected array $priceSpecifications;

	protected $seller;

	protected $warranty;

  /**
   * An alias for the item.
   */
  protected string $alternateName;

  protected string $description;

  protected $image;

  protected string $name;


	<<Collection>>
	public function getAcceptedPaymentMethods() : array
	{
		return $this->acceptedPaymentMethods;
	}

	public function setAcceptedPaymentMethods(array $value) : void
	{
		$this->acceptedPaymentMethods = $value;
	}

	<<Collection>>
	public function getAddons() : ?array
	{
			return $this->addons;
	}

	public function setAddons(array $values) : void
	{
		$this->addons = $values;
	}
	<<Int>>
	public function getAdvanceBookingRequirement()
	{
		return $this->advanceBookingRequirement;
	}

	public function setAdvanceBookingRequirement($value) : void
	{
		$this->advanceBookingRequirement = $value;
	}

	<<Int>>
	public function getItemAvailability() : ItemAvailability
	{
		return $this->availability;
	}

	public function setItemAvailability(ItemAvailability $value) : void
	{
		$this->availability = $value;
	}

	<<DateTime>>
	public function getAvailabilityEnds() : ?\DateTime
	{
		return $this->availabilityEnds;
	}

	public function setAvailabilityEnds(\DateTime $when) : void
	{
		$this->availabilityEnds = $when;
	}

	<<DateTime>>
	public function getAvailabilityStarts() : \DateTime
	{
		return $this->availabilityStarts;
	}

	public function setAvailabilityStarts(\DateTime $when) : void
	{
		$this->availabilityStarts = $when;
	}

	<<ObjectId>>
	public function getProductId() : \MongoId
	{
		return $this->productId;
	}

	public function setProductId(\MongoId $id) : void
	{
		$this->productId = $id;
	}

	<<Int>>
	public function getBusinessFunction() : BusinessFunction
	{
		return $this->businessFunction;
	}

	public function setBusinessFunction(BusinessFunction $value) : void
	{
		$this->businessFunction = $value;
	}

	<<String>>
	public function getCategory()
	{
		return $this->category;
	}

	public function setCategory(string $value) : void
	{
		$this->category = $value;
	}

	<<Int>>
	public function getDeliveryLeadTime() : int
	{
		return $this->deliveryLeadTime;
	}

	public function setDeliveryLeadTime(int $value) : void
	{
		$this->deliveryLeadTime = $value;
	}

	<<Int>>
	public function getEligibleCustomerType() : int
	{
		return $this->eligibleCustomerType;
	}

	public function setEligibleCustomerType(int $value) : void
	{
		$this->eligibleCustomerType = $value;
	}

	<<Int>>
	public function getItemCondition() : OfferItemCondition
	{
		return $this->itemCondition;
	}

	public function setItemCondition(OfferItemCondition $value) : void
	{
		$this->itemCondition = $value;
	}

	/*
	<<EmbedOne('Pi\ServiceModel\Types\Product')>>
	public function getItemOffered() : Product
	{
		return $this->itemOffered;
	}

	public function setItemOffered(Product $entity) : void
	{
		$this->itemOffered = $entity;
	}
	 */

	<<EmbedMany('Pi\ServiceModel\Types\PriceSpecification')>>
	public function getPriceSpecifications() : array
	{
		return $this->priceSpecifications;
	}

	public function setPriceSpecifications(array $values) : void
	{
		$this->priceSpecifications = $values;
	}

	<<Author>>
	public function getSeller()
	{
		return $this->seller;
	}

	public function setSeller($value) : void
	{
		$this->seller = $value;
	}

	public function getWarranty()
	{
		return $this->warranty;
	}

	public function setWarranty($value) : void
	{
		$this->warranty = $value;
	}

  <<String>>
  public function getAlternateName()
  {
    return $this->alternateName;
  }

  public function setAlternateName(string $name)
  {
    $this->alternateName = $name;
  }

  <<String>>
  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription(string $value)
  {
    $this->description = $value;
  }

  /**
   * @return mixed
   */
   <<String>>
  public function getImage()
  {
      return $this->image;
  }

  /**
   * @param mixed $image
   */
  public function setImage($image)
  {
      $this->image = $image;
  }

  <<String>>
  public function getName()
  {
    return $this->name;
  }

  public function setName(string $value)
  {
    $this->name = $value;
  }

}