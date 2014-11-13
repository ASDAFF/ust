<?php
namespace Bitrix\Main\Security\Sign;

use Bitrix\Main\ArgumentOutOfRangeException;

/**
 * Class HmacAlgorithm
 * @since 14.0.7
 * @package Bitrix\Main\Security\Sign
 */
class HmacAlgorithm
	extends SigningAlgorithm
{
	// ToDo: need option here?
	protected $hashAlgorithm = 'sha256';

	/**
	 * @param string $hashAlgorithm
	 * @return $this
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 */
	public function setHashAlgorithm($hashAlgorithm)
	{
		if (!in_array($hashAlgorithm, hash_algos()))
			throw new ArgumentOutOfRangeException('hashAlgorithm', hash_algos());

		$this->hashAlgorithm = $hashAlgorithm;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHashAlgorithm()
	{
		return $this->hashAlgorithm;
	}

	/**
	 * @param string $value
	 * @param string $key
	 * @return string
	 */
	public function getSignature($value, $key)
	{
		return hash_hmac($this->hashAlgorithm, $value, $key, true);
	}
}