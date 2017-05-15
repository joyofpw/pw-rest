# Processwire Rest Helper
A simple Rest Helper for Processwire 2.x or 3.x

comment https://github.com/NinjasCL/pw-rest/blob/master/rest/core/errors.php#L38
if you want to use PW 2.x

## Usage
Use this code inside your `templates` directory.

An example `login.php` and `404.php` template files
were made for demostration.


For a more complete example see
https://github.com/NinjasCL/voxgram

### Note 
For getting params for other methods than GET OR POST, you should send the request
as `application/json` object.

**Example**

```json
{
    "param1": "param1 value",
    "param2": "param2 value"
}
```

Made with <i class="fa fa-heart">&#9829;</i> by <a href="http://ninjas.cl" target="_blank">Ninjas</a>.

