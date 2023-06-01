<?php

namespace MpdfAnalize\Container;

interface ContainerInterface
{

	public function get($id);

	public function has($id);

}
