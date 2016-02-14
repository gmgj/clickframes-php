# Introduction #
This page details any perceived limitations in CodeIgniter that prevent us from fully implementing Clickframes code generation.

# Limitations #
## Fine-grained validation messages ##
CI defines a single validation message for each validation type (e.g. required, valid\_email, min\_length) or callback function.  Clickframes, however, specifies a unique validation message for field-validation combination.

  * http://codeigniter.com/forums/viewthread/124483/ suggests extending CI to support per-field messages