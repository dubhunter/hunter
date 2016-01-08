<div id="alert-container">
	{% if flash.getMessages()|length %}
		{% for type, messages in flash.getMessages() %}
			{% for message in messages %}
				<div class="alert alert-{{ type == 'notice' ? 'info' : type }} alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ message }}
				</div>
			{% endfor %}
		{% endfor %}
	{% endif %}
</div>
