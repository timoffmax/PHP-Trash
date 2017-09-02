# PHP-Trash

This repository contain some scripts and tiny apps for solving casual or highly specialized tasks.

## List of scripts
 - ### switch_audio_output.php
    This script can switch all active audio channels to next available audio output in system.
    Only for Pulseaudio!
    
    #### Installing dependent packages
    ```sh
    apt-get install pulseaudio-utils php7.0
    ```
    
    #### How to use
    Just run script without any params. 
    
    Script get all available audio outputs in your system, detect active output then try to switch to next output.
     
    You can set your own combination of keys for simple run this script.
