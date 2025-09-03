<?php
/**
 * @license MIT
 *
 * Modified on 30-June-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace BitCode\BitFormPro\Dependencies\Mpdf\PsrLogAwareTrait;

use BitCode\BitFormPro\Dependencies\Psr\Log\LoggerInterface;

trait PsrLogAwareTrait 
{

	/**
	 * @var \BitCode\BitFormPro\Dependencies\Psr\Log\LoggerInterface
	 */
	protected $logger;

	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
	
}
