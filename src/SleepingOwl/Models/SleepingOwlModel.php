<?php namespace SleepingOwl\Models;

use Carbon\Carbon;
use InvalidArgumentException;
use SleepingOwl\Models\Interfaces\ValidationModelInterface;
use SleepingOwl\Models\Traits\DeleteAllModelTrait;
use SleepingOwl\Models\Traits\ModelWithImageOrFileFieldsTrait;
use SleepingOwl\Models\Traits\ValidationModelTrait;

/**
 * @method static defaultSort()
 */
abstract class SleepingOwlModel extends \Eloquent implements ValidationModelInterface
{
	use DeleteAllModelTrait, ValidationModelTrait, ModelWithImageOrFileFieldsTrait;

	/**
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function newQuery()
	{
		$query = parent::newQuery();
		$query->defaultSort();
		return $query;
	}

	/**
	 * @param $query
	 */
	public function scopeDefaultSort($query)
	{
		return $query;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function withoutOrders()
	{
		$instance = new static;
		$query = $instance->newQuery();
		$query->getQuery()->orders = [];
		return $query;
	}

	/**
	 * @return \Eloquent
	 */
	public static function random()
	{
		return static::withoutOrders()->orderByRaw('RAND()')->first();
	}

	/**
	 * @param \DateTime|int $value
	 * @return Carbon|string
	 */
	public function fromDateTime($value)
	{
		try
		{
			$result = parent::fromDateTime($value);
		} catch (InvalidArgumentException $e)
		{
			$format = $this->getDateFormat();
			$value = new Carbon($value);
			return $value->format($format);
		}
		return $result;
	}


}