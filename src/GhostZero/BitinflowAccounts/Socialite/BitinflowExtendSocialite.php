<?php

namespace GhostZero\BitinflowAccounts\Socialite;

use SocialiteProviders\Manager\SocialiteWasCalled;

/**
 * @author René Preuß <rene@preuss.io>
 */
class BitinflowExtendSocialite
{

    /**
     * Register the provider.
     *
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'bitinflow-accounts', __NAMESPACE__ . '\Provider'
        );
    }
}