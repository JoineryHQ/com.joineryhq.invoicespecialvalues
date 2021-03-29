# Invoice Special Values

Provides an api invoicespecialvalues.get to provide additional data for invoices.

See this at work by adding code like the following to the "Contributions - Invoice" message template:

```
{crmAPI var='result' entity='invoicespecialvalues' action='get' id=$id}
{foreach from=$result.values item=value}
  {$value.custom_field_value}<br>
  {$value.receive_date}<br>
  {foreach from=$value.participant_names item=participant_name}
    {$participant_name}<br>
  {/foreach}
  {foreach from=$value.payments_received item=payment_received}
    {$payment_received.date} :: {$payment_received.amount}<br>
  {/foreach}
{/foreach}
```

## Support
![screenshot](/images/joinery-logo.png)

Joinery provides services for CiviCRM including custom extension development, training, data migrations, and more. We aim to keep this extension in good working order, and will do our best to respond appropriately to issues reported on its [github issue queue](https://github.com/JoineryHQ/com.joineryhq.invoicespecialvalues/issues). In addition, if you require urgent or highly customized improvements to this extension, we may suggest conducting a fee-based project under our standard commercial terms.  In any case, the place to start is the [github issue queue](https://github.com/JoineryHQ/com.joineryhq.invoicespecialvalues/issues) -- let us hear what you need and we'll be glad to help however we can.

And, if you need help with any other aspect of CiviCRM -- from hosting to custom development to strategic consultation and more -- please contact us directly via https://joineryhq.com
