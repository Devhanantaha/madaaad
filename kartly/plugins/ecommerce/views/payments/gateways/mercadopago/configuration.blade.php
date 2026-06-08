@php
    $currencies = getAllCurrencies();
    $selected_currency = \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(
        $method->id,
        'mc_pago_currency',
    );
    $default_currency = $selected_currency == null ? '' : $selected_currency;
@endphp
<div class="p-3 payment-method-item-body border-top2">
    <div class="configuration">
        <form id="credential-form-{{ $method->id }}">
            <input type="hidden" name="payment_id" value="{{ $method->id }}">
            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Logo') }}</label>
                <div class="input-option">
                    @include('core::base.includes.media.media_input', [
                        'input' => 'mc_pago_logo',
                        'data' => \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue(
                            $method->id,
                            'mc_pago_logo'),
                    ])
                </div>
            </div>
            <div class="form-group mb-20">
                <label class="black bold">{{ translate('Currency') }}</label>
                <div class="mb-2">
                    <a href="{{ route('plugin.ecommerce.ecommerce.all.currencies') }}"
                        class="mt-2">({{ translate('Please setup exchange rate for the chosen currency') }})</a>
                </div>
                <div class="input-option">
                    <select name="mc_pago_currency" class="theme-input-style selectCurrency">
                        <option value="">{{ translate('Select Currency') }}</option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->code }}" class="text-uppercase"
                                {{ $currency->code == $default_currency ? 'selected' : '' }}>
                                {{ $currency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Access Token') }}</label>
                <div class="input-option">
                    <input type="text" class="theme-input-style" name="mc_pago_access_token"
                        placeholder="Enter Access Token"
                        value="{{ \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'mc_pago_access_token') }}"
                        required />
                </div>
            </div>
            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Public Key') }}</label>
                <div class="input-option">
                    <input type="text" class="theme-input-style" name="mc_pago_public_key"
                        placeholder="Enter Public Key"
                        value="{{ \Plugin\Ecommerce\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'mc_pago_public_key') }}"
                        required />
                </div>
            </div>

            <div class="form-group mb-20">
                <label class="black bold mb-2">{{ translate('Payment Instructions') }}</label>
                <div class="input-option">
                    <textarea name="mc_pago_instruction" class="theme-input-style" placeholder="Enter Instruction">{{ \Plugin\ecommerce\Repositories\PaymentMethodRepository::configKeyValue($method->id, 'mc_pago_instruction') }}</textarea>
                    <small class="text-muted"><i class="icofont-info-circle"></i>
                        {{ translate('Customer will see this instructions when paying with Mercado Pago') }}</small>
                </div>
            </div>
            <div>
                <button class="btn long payment-credential-update-btn"
                    data-payment-btn="{{ $method->id }}">{{ translate('Save Changes') }}</button>
            </div>
        </form>
    </div>
    <div class="instruction ml-lg-5">
        <a href="https://www.mercadopago.com.co/" target="_blank">Mercado Pago</a>
        <p>
            {{ translate('Customer can subscribe and pay directly using Mercado Pago') }}
        </p>
        <p class="semi-bold">
            {{ translate('Configuration instruction for Mercado Pago') }}
        </p>
        <p>{{ translate('To use Mercado Pago, you need:') }}</p>
        <ol>
            <li style="list-style-type: decimal">
                <a href="https://www.mercadopago.com.co/hub/registration/splitter?contextual=normal&entity=pf">
                    {{ translate('Register with Mercado Pago') }}
                </a>
            </li>
            <li style="list-style-type: decimal">
                <p>
                    {{ translate('After registration at Mercado Pago, you will have Access Token and Public Key') }}
                </p>
            </li>
            <li style="list-style-type: decimal">
                <p>
                    {{ translate('Enter Access Token and Public Key into the box in left hand') }}
                </p>
            </li>
        </ol>
    </div>
</div>
