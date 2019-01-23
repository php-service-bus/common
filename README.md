[![Build Status](https://travis-ci.org/php-service-bus/common.svg?branch=master)](https://travis-ci.org/php-service-bus/common)
[![Code Coverage](https://scrutinizer-ci.com/g/php-service-bus/common/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/php-service-bus/common/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-service-bus/common/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-service-bus/common/?branch=master)

## What is it?
Common parts for components of the [service-bus](https://github.com/php-service-bus/service-bus) framework

Functions:
* [uuid()](https://github.com/php-service-bus/common/blob/master/src/functions.php#L29): Generate a version 4 (random) UUID
* [datetimeInstantiator()](https://github.com/php-service-bus/common/blob/master/src/functions.php#L45): Creating an instance of an \DateTimeImmutable from a text
* [datetimeToString()](https://github.com/php-service-bus/common/blob/master/src/functions.php#L78): Format DateTimeImmutable to string
* [invokeReflectionMethod()](https://github.com/php-service-bus/common/blob/master/src/functions.php#L105): Invokes a reflected method
* [writeReflectionPropertyValue()](https://github.com/php-service-bus/common/blob/master/src/functions.php#L131): Write property value
* [readReflectionPropertyValue()](https://github.com/php-service-bus/common/blob/master/src/functions.php#L151): Read property value
* [createWithoutConstructor()](https://github.com/php-service-bus/common/blob/master/src/functions.php#L208): Creates a new class instance without invoking the constructor

Messages:
* [Message](https://github.com/php-service-bus/common/blob/master/src/Messages/Message.php): Message marker (command/event)
* [Command](https://github.com/php-service-bus/common/blob/master/src/Messages/Command.php): Used to request that an action should be taken
* [Event](https://github.com/php-service-bus/common/blob/master/src/Messages/Event.php): Used to communicate that some action has taken place

Context:
* [ServiceBusContext](https://github.com/php-service-bus/common/blob/master/src/Context/ServiceBusContext.php): Message execution context interface

Endpoint:
* [DeliveryOptions](https://github.com/php-service-bus/common/blob/master/src/Endpoint/DeliveryOptions.php): Interface indicating the configuration of message sending parameters

Module:
* [ServiceBusModule](https://github.com/php-service-bus/common/blob/master/src/Module/ServiceBusModule.php): Application module interface
