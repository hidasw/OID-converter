# OID-converter
yet simplest php script to convert OID to hex and vice versa  
## Usage
- To convert OID number to hex:  
``oid::tohex($oid)`` where ``$oid`` is oid number ie:1.2.34.56547.54
  - first number limited to 0..2
  - second number limited to 0..39
  - next other number limited to 0..9999999999 (10 digit max)  
  
- To convert hex form to OID:  
  ``oid::fromhex($hex)`` where ``$hex`` is hex  