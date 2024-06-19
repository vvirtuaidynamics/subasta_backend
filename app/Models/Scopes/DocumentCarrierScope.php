<?php


namespace App\Models\Scopes;


use App\Enums\ValidationStatus;
use App\Http\Repositories\Module\ValidationTask\ValidationTaskRepository;
use Illuminate\Support\Carbon;
use Matrix\Builder;

trait DocumentCarrierScope
{
  public function scopeUnvalidated($query)
  {
    return $query->where('validated', '');
  }

  public function scopeExpireDocuments($query)
  {
    return $query->where('expire_date', '<', Carbon::now());
  }


}
