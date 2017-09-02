<?php
/*
 * Get all available outputs
 */
function getAllSinks() : array {
	$shellCommand = "pacmd list-sinks | awk '/name:/{print}' | tr -d '\t<>' | cut -f 2 -d ' '";
	$allSinks = shell_exec($shellCommand);
	$allSinks = preg_split('/\n/', $allSinks, -1, PREG_SPLIT_NO_EMPTY);

	return $allSinks ?? [];
}

/*
 * Get active output position in array of all outputs
 */
function getDefaultSink() : array {
	// Get all outputs
	$allSinks = getAllSinks();

	// Get current active output as string
	$shellCommand = "pacmd list-sinks | awk '/\*.*index/{getline; print}' | tr -d '\t<>' | cut -f 2 -d ' '";
	$defaultSinkName = trim(shell_exec($shellCommand));
	$defaultSinkPosition = array_search($defaultSinkName, $allSinks);

	$defaultSink = [
		'name' => $defaultSinkName,
		'position' => $defaultSinkPosition
	];
	
	return $defaultSink ?? '';
}

function switchToNextOutput() {
	// Get active output and all outputs
	$defaultSink = getDefaultSink();
	$allSinks = getAllSinks();

	// Get all active audio channels indexes
	$currentActiveOutputs = shell_exec("pacmd list-sink-inputs | awk '/index:/{print $2}' | xargs -r -I{} echo {}");
	$currentActiveOutputs = preg_split('/\n/', $currentActiveOutputs, -1, PREG_SPLIT_NO_EMPTY);

	if (empty($defaultSink)) {
		throw new \Exception("Can't define current default sink!");
	}

	echo "Current default sink: " . $defaultSink['name'] . "\n";
	
	// Define next output in array (after active)
	$nextAvailableSink = $allSinks[$defaultSink['position'] + 1] ?? $allSinks[0] ?? '';
	$nextAvailableSink = trim($nextAvailableSink);

	if (empty($nextAvailableSink)) {
		throw new \Exception("Can't find output for switch!");
	}

	// Change default output
	shell_exec("pacmd set-default-sink {$nextAvailableSink}");

	// Check changes
	$newDefaultSink = getDefaultSink();
	
	if ($newDefaultSink['name'] !== $defaultSink['name']) {
		echo "Output has been switched from {$defaultSink['name']} to {$nextAvailableSink}.\n";
	} else {
		throw new Exception("Can't switch from {$defaultSink['name']} to {$nextAvailableSink}}");
	}

	// Switch all active audio channels to new default output
	foreach ($currentActiveOutputs as $channelIndex) {
		echo "pacmd move-sink-input " . $channelIndex . " " . $nextAvailableSink . "\n";
		passthru("pacmd move-sink-input {$channelIndex} {$nextAvailableSink}");
		echo "Channel {$channelIndex} has been switched to {$nextAvailableSink}.\n";
	}
}

// Run
switchToNextOutput();
