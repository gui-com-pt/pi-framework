<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceModel\FindUser,
    Pi\ServiceModel\FindUserResponse,
    Pi\Auth\UserRepository,
    Pi\Service;




class FindUserService extends Service {

    public UserRepository $userRepo;

    <<Request,Route('/api/user')>>
    public function normal(FindUser $request)
    {
      $response = new FindUserResponse();
      $users = $this->userRepo
        ->queryBuilder('Pi\ServiceModel\UserDto')
        ->find()
        ->hydrate()
        ->getQuery()
        ->execute();
        $response->setUsers($users);
      return $response;
    }
}
