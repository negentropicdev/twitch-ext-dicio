# twitch-roverlay
Source code for Twitch Controls overlays

Definitions:

 - User - Twitch users, optionally fully identified to provide username, avatar, and join date. Both streamers and viewers
 - Channel - Tied to a user and contains ControlSets and Systems as well as global queues and enable flags
 - ControlSet - Extension configuration that describes controls present in overlay. Specific to a channel. Each can be configured to require ID. Open access or assigned (by streamer/queue)
 - Client - Remote software that can read/write ControlSet data. Specific to a channel.
 - Queue - Tied to an array of ControlSets which can be empty for a manually pulled queue controlled by streamer. Can configure strategies for distributing users to ControlSets.

Controls:

 - Button - Momentary or toggle, default true or false
 - Slider - Numeric range, integer or float
 - DualAxis - Joystick, optional momentary
 - 