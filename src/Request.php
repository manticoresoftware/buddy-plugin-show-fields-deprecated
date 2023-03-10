<?php declare(strict_types=1);

/*
  Copyright (c) 2023, Manticore Software LTD (https://manticoresearch.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 2 or any later
  version. You should have received a copy of the GPL license along with this
  program; if you did not, you can find it at http://www.gnu.org/
*/
namespace Manticoresearch\Buddy\Plugin\ShowFields;

use Manticoresearch\Buddy\Core\Error\QueryParseError;
use Manticoresearch\Buddy\Core\Network\Request as NetworkRequest;
use Manticoresearch\Buddy\Core\Plugin\Request as BaseRequest;

final class Request extends BaseRequest {
	public string $path;

	public function __construct(public string $table) {
	}

  /**
	 * @param NetworkRequest $request
	 * @return static
	 */
	public static function fromNetworkRequest(NetworkRequest $request): static {
		$pattern = '#show fields from'
			. '\s+`?(?P<table>([a-z][a-z0-9\_]*))`?'
			. '$#ius';
		if (!preg_match($pattern, $request->payload, $m)) {
			throw QueryParseError::create('You have an error in your query. Please, double-check it.');
		}

		$self = new static($m['table']);
		$self->path = $request->path;
		return $self;
	}

	/**
	 * @param NetworkRequest $request
	 * @return bool
	 */
	public static function hasMatch(NetworkRequest $request): bool {
		return stripos($request->payload, 'show fields') === 0;
	}
}
