[![Build Status](https://travis-ci.org/php-service-bus/common.svg?branch=master)](https://travis-ci.org/php-service-bus/common)
[![Code Coverage](https://scrutinizer-ci.com/g/php-service-bus/common/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/php-service-bus/common/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-service-bus/common/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-service-bus/common/?branch=master)

## What is it?
Common parts for components of the [service-bus](https://github.com/php-service-bus/service-bus) framework

List of implemented functions:
* [uuid()](https://github.com/mmasiukevich/common/blob/master/src/functions.php#L29): Generate a version 4 (random) UUID
* [datetimeInstantiator()](https://github.com/mmasiukevich/common/blob/master/src/functions.php#L67): Creating an instance of an \DateTimeImmutable from a text
* [datetimeToString()](https://github.com/mmasiukevich/common/blob/master/src/functions.php#L100): Format DateTimeImmutable to string
* [invokeReflectionMethod()](https://github.com/mmasiukevich/common/blob/master/src/functions.php#L127): Invokes a reflected method
* [writeReflectionPropertyValue()](https://github.com/mmasiukevich/common/blob/master/src/functions.php#L153): Write property value
* [readReflectionPropertyValue()](https://github.com/mmasiukevich/common/blob/master/src/functions.php#L173): Read property value
* [createWithoutConstructor()](https://github.com/mmasiukevich/common/blob/master/src/functions.php#L230): Creates a new class instance without invoking the constructor

Interfaces:
* [Message](https://github.com/mmasiukevich/common/blob/master/src/Messages/Message.php): Message marker (command/event)
* [Command](https://github.com/mmasiukevich/common/blob/master/src/Messages/Command.php): Used to request that an action should be taken
* [Event](https://github.com/mmasiukevich/common/blob/master/src/Messages/Event.php): Used to communicate that some action has taken place