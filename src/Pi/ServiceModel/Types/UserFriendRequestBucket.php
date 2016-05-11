<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\BucketCollection;

class UserFriendRequestBucket extends BucketCollection{
	
	protected $data;

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    <<Collection('\MongoId')>>
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the value of data.
     *
     * @param mixed $data the data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }	
}