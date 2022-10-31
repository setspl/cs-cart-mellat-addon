<div class="control-group">
    <label
        class="control-label cm-required"
        for="terminalId_{$payment_id}"
    >
        {__("behpardakht_mellat.terminalId")}:
    </label>
    
    <div class="controls">
        <input
            type="text"
            name="payment_data[processor_params][terminalId]"
            id="terminalId_{$payment_id}"
            value="{$processor_params.terminalId}"
            size="60"
        />
    </div>
</div>

<div class="control-group">
    <label
        class="control-label cm-required"
        for="userName_{$payment_id}"
    >
        {__("behpardakht_mellat.userName")}:
    </label>
    
    <div class="controls">
        <input
            type="text"
            name="payment_data[processor_params][userName]"
            id="userName_{$payment_id}"
            value="{$processor_params.userName}"
            size="60"
        />
    </div>
</div>

<div class="control-group">
    <label
        class="control-label cm-required"
        for="userPassword_{$payment_id}"
    >
        {__("behpardakht_mellat.userPassword")}:
    </label>
    
    <div class="controls">
        <input
            type="password"
            name="payment_data[processor_params][userPassword]"
            id="userPassword_{$payment_id}"
            value="{$processor_params.userPassword}"
            size="60"
        />
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="test_{$payment_id}">{__("test_live_mode")}:</label>
    
    <div class="controls">
        <select name="payment_data[processor_params][test]" id="test_{$payment_id}">
            <option value="N" {if $processor_params.test === "YesNo::NO"|enum}selected="selected"{/if}>{__("live")}</option>
            <option value="Y" {if $processor_params.test === "YesNo::YES"|enum}selected="selected"{/if}>{__("test")}</option>
        </select>
    </div>
</div>