<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
{{ Illuminate\Mail\Markdown::parse("*$name's information. if this isn't you please don't see it.") }}
{{ Illuminate\Mail\Markdown::parse('*This is an automated message - Please don\'t reply directly to this message.') }}
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
