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

Support for this package is handled under Joinery's ["As-Is Support" policy](https://joineryhq.com/software-support-levels#as-is-support).

Public issue queue for this package: https://github.com/JoineryHQ/com.joineryhq.invoicespecialvalues/issues
