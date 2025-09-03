<?php
/**
 * @license GPL-2.0-only
 *
 * Modified on 30-June-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace BitCode\BitFormPro\Dependencies\Mpdf\Tag;

class NewColumn extends Tag
{

	public function open($attr, &$ahtml, &$ihtml)
	{
		$this->mpdf->ignorefollowingspaces = true;
		$this->mpdf->NewColumn();
		$this->mpdf->ColumnAdjust = false; // disables all column height adjustment for the page.
	}

	public function close(&$ahtml, &$ihtml)
	{
	}
}
