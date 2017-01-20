<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Hunter Light | {% block title %}Night Light API{% endblock %}</title>

		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<meta name="author" content="Will Mason">
		<meta name="description" content="Twilio shout out web service.">

		<link rel="shortcut icon" href="/favicons/monkey-64.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/favicons/monkey-180.png">

		{{ assets.outputCss() }}
	</head>
	<body{% block bodyAttributes %}{% endblock %}>
		{# Declare the main navigation area #}
		{% block navigation %}
			{% include 'layouts/includes/header.volt' %}
		{% endblock %}

		<div class="container">
			{% block flashMessages %}
				{% include 'layouts/includes/flash-messages.volt' %}
			{% endblock %}

			{% block content %}{% endblock %}

			{% block footer %}
				{% include 'layouts/includes/footer.volt' %}
			{% endblock %}
		</div>

		{{ assets.outputJs() }}
	</body>
</html>
