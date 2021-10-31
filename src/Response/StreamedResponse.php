<?php

namespace audunru\ExportResponse\Response;

use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\StreamedResponse as SymfonyStreamedResponse;

class StreamedResponse extends SymfonyStreamedResponse
{
    use Macroable {
      Macroable::__call as macroCall;
  }
}
