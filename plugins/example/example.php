<?php
class Example extends Plugin {

	// Demonstrates how to add a separate panel to the preferences screen and inject Javascript/save data using Dojo forms.

	private $link;
	private $host;

	function __construct($host) {
		$this->link = $host->get_link();
		$this->host = $host;

		$host->add_hook($host::HOOK_PREFS_TAB, $this);
	}

	function save() {
		$example_value = db_escape_string($_POST["example_value"]);

		echo "Value set to $example_value (not really)";
	}

	function get_prefs_js() {
		return file_get_contents(dirname(__FILE__) . "/example.js");
	}

	function hook_prefs_tab($args) {
		if ($args != "prefPrefs") return;

		print "<div dojoType=\"dijit.layout.AccordionPane\" title=\"".__("Example Pane")."\">";

		print "<form dojoType=\"dijit.form.Form\">";

		print "<script type=\"dojo/method\" event=\"onSubmit\" args=\"evt\">
			evt.preventDefault();
			if (this.validate()) {
				console.log(dojo.objectToQuery(this.getValues()));
				new Ajax.Request('backend.php', {
					parameters: dojo.objectToQuery(this.getValues()),
					onComplete: function(transport) {
						notify_info(transport.responseText);
					}
				});
				this.reset();
			}
			</script>";

			print "<input dojoType=\"dijit.form.TextBox\" style=\"display : none\" name=\"op\" value=\"pluginhandler\">";
			print "<input dojoType=\"dijit.form.TextBox\" style=\"display : none\" name=\"method\" value=\"save\">";
			print "<input dojoType=\"dijit.form.TextBox\" style=\"display : none\" name=\"plugin\" value=\"example\">";

		print "<table width=\"100%\" class=\"prefPrefsList\">";

			print "<tr><td width=\"40%\">".__("Sample value")."</td>";
			print "<td class=\"prefValue\"><input dojoType=\"dijit.form.ValidationTextBox\" required=\"1\" name=\"example_value\"></td></tr>";

			print "</table>";

			print "<p><button dojoType=\"dijit.form.Button\" type=\"submit\">".
				__("Set value")."</button>";

			print "</form>";

		print "</div>"; #pane
	}
}
?>