<?php

namespace Issues;

/**
 * Image transformations
 */
class Issue1079Test extends \Mpdf\BaseMpdfTest
{
	const IMAGE = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPj/HwADBwIAMCbHYQAAAABJRU5ErkJggg==" width="425" height="95">';

	public function setUp()
	{
		parent::setUp();
		$this->mpdf->SetCompression(false);
	}

	public function testImageTransformTranslateXY()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: translate(25mm, 50mm);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 1.0000 0.0000 0.0000 1.0000 70.8661 -141.7323 cm  
Q
q 1.0000 0.0000 0.0000 1.0000 70.8661 -141.7323 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 0.0000 0.0000 1.0000 70.8661 -141.7323 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformTranslateX()
	{
		foreach (['translate', 'translateX'] as $transformation) {
			$this->mpdf->WriteHTML('
			<style>
				img {
					transform: ' . $transformation . '(25mm);
				}
			</style>
			' . self::IMAGE);
			$output = $this->mpdf->Output('', 'S');

			$this->assertStringStartsWith('%PDF-', $output);
			$this->assertContains('q 1.0000 0.0000 0.0000 1.0000 70.8661 0.0000 cm  
Q
q 1.0000 0.0000 0.0000 1.0000 70.8661 0.0000 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 0.0000 0.0000 1.0000 70.8661 0.0000 cm  
[] 0 d
/GS1 gs
Q', $output);
		}
	}

	public function testImageTransformTranslateY()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: translateY(50mm);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 1.0000 0.0000 0.0000 1.0000 0.0000 -141.7323 cm  
Q
q 1.0000 0.0000 0.0000 1.0000 0.0000 -141.7323 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 0.0000 0.0000 1.0000 0.0000 -141.7323 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformScaleXY()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: scale(0.25, 0.5);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 0.2500 0.0000 0.0000 0.5000 151.4210 380.4553 cm  
Q
q 0.2500 0.0000 0.0000 0.5000 151.4210 380.4553 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 0.2500 0.0000 0.0000 0.5000 151.4210 380.4553 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformScaleWithAspectRatio()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: scale(0.5);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 0.5000 0.0000 0.0000 0.5000 100.9473 380.4553 cm  
Q
q 0.5000 0.0000 0.0000 0.5000 100.9473 380.4553 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 0.5000 0.0000 0.0000 0.5000 100.9473 380.4553 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformScaleX()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: scaleX(0.5);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 0.5000 0.0000 0.0000 1.0000 100.9473 0.0000 cm  
Q
q 0.5000 0.0000 0.0000 1.0000 100.9473 0.0000 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 0.5000 0.0000 0.0000 1.0000 100.9473 0.0000 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformScaleY()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: scaleY(0.5);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 1.0000 0.0000 0.0000 0.5000 0.0000 380.4553 cm  
Q
q 1.0000 0.0000 0.0000 0.5000 0.0000 380.4553 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 0.0000 0.0000 0.5000 0.0000 380.4553 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformSkewXY()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: skew(15deg, 30deg);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 1.0000 -0.5774 -0.2679 1.0000 203.8854 116.5640 cm  
Q
q 1.0000 -0.5774 -0.2679 1.0000 203.8854 116.5640 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 -0.5774 -0.2679 1.0000 203.8854 116.5640 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformSkewX()
	{
		foreach (['skew', 'skewX'] as $transformation) {
			$this->mpdf->WriteHTML('
			<style>
				img {
					transform: ' . $transformation . '(15deg);
				}
			</style>
			' . self::IMAGE);
			$output = $this->mpdf->Output('', 'S');

			$this->assertStringStartsWith('%PDF-', $output);
			$this->assertContains('q 1.0000 0.0000 -0.2679 1.0000 203.8854 0.0000 cm  
Q
q 1.0000 0.0000 -0.2679 1.0000 203.8854 0.0000 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 0.0000 -0.2679 1.0000 203.8854 0.0000 cm  
[] 0 d
/GS1 gs
Q', $output);
		}
	}

	public function testImageTransformSkewY()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: skewY(30deg);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 1.0000 -0.5774 0.0000 1.0000 0.0000 116.5640 cm  
Q
q 1.0000 -0.5774 0.0000 1.0000 0.0000 116.5640 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 -0.5774 0.0000 1.0000 0.0000 116.5640 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testImageTransformRotate()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: rotate(45deg);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 0.7071 -0.7071 0.7071 0.7071 -478.9115 365.6267 cm  
Q
q 0.7071 -0.7071 0.7071 0.7071 -478.9115 365.6267 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 0.7071 -0.7071 0.7071 0.7071 -478.9115 365.6267 cm  
[] 0 d
/GS1 gs
Q', $output);
	}

	public function testMultipleImageTransformations()
	{
		$this->mpdf->WriteHTML('
		<style>
			img {
				transform: translate(25mm 50mm) scale(2.0);
			}
		</style>
		' . self::IMAGE);
		$output = $this->mpdf->Output('', 'S');

		$this->assertStringStartsWith('%PDF-', $output);
		$this->assertContains('q 1.0000 0.0000 0.0000 1.0000 70.8661 -141.7323 cm 2.0000 0.0000 0.0000 2.0000 -201.8947 -760.9107 cm  
Q
q 1.0000 0.0000 0.0000 1.0000 70.8661 -141.7323 cm 2.0000 0.0000 0.0000 2.0000 -201.8947 -760.9107 cm 318.750 0 0 71.250 42.520 725.286 cm /I2 Do Q
q 1.0000 0.0000 0.0000 1.0000 70.8661 -141.7323 cm 2.0000 0.0000 0.0000 2.0000 -201.8947 -760.9107 cm  
[] 0 d
/GS1 gs
Q', $output);
	}
}
