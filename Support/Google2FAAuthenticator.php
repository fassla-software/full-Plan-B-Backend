<?php

namespace Support;

use PragmaRX\Google2FALaravel\Exceptions\InvalidOneTimePassword;
use PragmaRX\Google2FALaravel\Exceptions\InvalidSecretKey;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FAAuthenticator extends Authenticator
{
    protected function canPassWithoutCheckingOTP()
    {
        if($this->getUser()->google_2fa_secret == null)
            return true;
        return
            !$this->getUser()->google_2fa_enable_disable_disable ||
            !$this->isEnabled() ||
            $this->noUserIsAuthenticated() ||
            $this->twoFactorAuthStillValid();
    }

    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->google_2fa_secret->{$this->config('otp_secret_column')};

        if (is_null($secret) || empty($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }

    /**
     * Verify the OTP.
     *
     * @throws InvalidOneTimePassword
     *
     * @return mixed
     */
    public function verifyOneTimePasswordManually($oneTimePassword)
    {
        return $this->verifyAndStoreOneTimePassword($oneTimePassword);
    }



}
