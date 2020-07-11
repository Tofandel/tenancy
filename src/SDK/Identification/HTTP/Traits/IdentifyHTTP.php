<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\SDK\Identification\HTTP\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tenancy\Identification\Contracts\Tenant;

trait IdentifyHTTP
{
    /**
     * Identifies the tenant through the mode specified in the config.
     *
     * @param Request $request
     *
     * @return Tenant|null
     */
    public function tenantIdentificationByHttp(Request $request): ?Tenant
    {
        $function = 'httpIdentify'.config('tenancy.sdk.identification.http.mode');

        return $this->{$function}($request);
    }

    /**
     * Always identifies as null.
     *
     * @param Request $request
     *
     * @return null
     */
    protected function httpIdentifyNone(Request $request)
    {
        return null;
    }

    /**
     * Always identifies as the first of the query.
     *
     * @param Request $request
     *
     * @return null
     */
    protected function httpIdentifyFirst(Request $request)
    {
        return $this->newQuery()->first();
    }

    /**
     * Dump dies the request.
     *
     * @param Request $request
     *
     * @return void
     */
    protected function httpIdentifyDie(Request $request)
    {
        dd($request);
    }

    /**
     * Dumps the response and returns null.
     *
     * @param Request $request
     *
     * @return null
     */
    protected function httpIdentifyDump(Request $request)
    {
        dump($request);

        return null;
    }

    /**
     * Identifies the tenant based on a host key on the tenant.
     *
     * @param Request $request
     *
     * @return Tenant|null
     */
    protected function httpIdentifyTenantHost(Request $request)
    {
        return $this->newQuery()
            ->where('host', $request->getHost())
            ->first();
    }

    /**
     * Identifies the tenant based on subdomain as the tenant key on the tenant.
     *
     * @param Request $request
     *
     * @return Tenant|null
     */
    protected function httpIdentifyTenantSubdomainKey(Request $request)
    {
        return $this->newQuery()
            ->where($this->getTenantKeyName(), Str::before($request->getHost(), '.'))
            ->first();
    }
}