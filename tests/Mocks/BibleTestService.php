<?hh

namespace Mocks;

use Pi\Service,
    Mocks\VerseGet,
    Mocks\VerseById,
    Mocks\VerseCreateRequest,
    Mocks\VerseCreateResponse,
    Mocks\VerseGetResponse;




class BibleTestService extends Service {
    
    <<Method('GET'),Route('/test')>>
    public function get(VerseGet $request) : VerseGetResponse
    {
      $response = new VerseGetResponse();
      return $response;
    }
    <<Method('GET'),Route('/test-other')>>
    public function other(VerseGet $request) : VerseGetResponse
    {
      return new VerseGetResponse();
    }
    
    <<Method('POST'),Route('/test')>>
    public function post(VerseCreateRequest $request) : VerseCreateResponse
    {
      $response = new VerseCreateResponse();
      return $response;
    }

    <<Method('GET'),Route('/verse/:id')>>
    public function getById(VerseById $request) : VerseGetResponse
    {
      $response = new VerseGetResponse();
      return $response;
    }
}