<div class="border-top2 p-3 payment-method-item-body">
    <div class="configuration">
        <form id="credential-form-{{ $method->id }}">
            <input type="hidden" name="payment_id" value="{{ $method->id }}">
            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Logo') }}</label>
                <div class="input-option">
                    @include('core::base.includes.media.media_input', [
                        'input' => 'cod_logo',
                        'data' => \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(
                            config('ecommerce.payment_methods.cod'),
                            'cod_logo'),
                    ])
                    @if ($errors->has('cod_logo'))
                        <div class="invalid-input">{{ $errors->first('cod_logo') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Payment Instructions') }}</label>
                <div class="input-option">
                    <textarea name="cod_instruction" class="theme-input-style">{{ \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(config('ecommerce.payment_methods.cod'), 'cod_instruction') }}</textarea>
                    <small class="text-muted"><i class="icofont-info-circle"></i>
                        {{ translate('Customer will see this instructions when paying with Cash on Delivery') }}</small>
                </div>
            </div>
            <div>
                <button class="btn long payment-credential-update-btn"
                    data-payment-btn="{{ $method->id }}">{{ translate('Save Changes') }}</button>
            </div>
        </form>
    </div>
</div>
