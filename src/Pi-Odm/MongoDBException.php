<?hh

namespace Pi\Odm;

class MongoDBException 
	extends \Exception {

	public static function invalidFindByCall($documentName, $fieldName, $method)
	{
		return new self(sprintf('Invalid find by call %s field %s method %s', $documentName, $fieldName, $method));
	}

	public static function notUpdated(array $query)
	{
		return new self(sprintf('Document wasnt updated. %s', print_r($query)));
	}
}