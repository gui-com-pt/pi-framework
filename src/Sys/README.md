# System Administration

Sys (System Administration) is a composite application developed with Pi Framework. It manage single Servers or Clusters using the Pi builtin Service Discovery and Services that not only manage the servers instances but also provide others products as `WebhostService` and `DomainService`.

Those services can be configured with the `FileSystemPlugin`. In the example of `WebhostService` the endpoint could be at https://example.com/api/host but the actual filesystem been used could stored in other server.

By default, `PiHost` handles scalling providing a fallback with `JsonServiceClient` for Requet DTOs that aren't available at the host. Service Provider contains Services that allow hosts to register them selfs.

On it's core, Pi is just a chassis for micro services. To store filesystem data we use the `FileSystemPlugin`, the `OdmPlugin` for databases, etc. Pi is only responsible for redirecting requests to others available Services if not found. So a Request ocorring in host A or host B is supposed to have same effect as the backend storages options scale .

Sys hosts are not exactly the same as PiHost, instead they identify uniquely each hostname. So Requests DTOs are hostname specific (like create user entry in the system) providing the ServerId.

## Architecture

All users are prefixed with pi. As far as Pi is concerned the highest user is the default 'admin'. Admin sits at the very top of food chain and has complete control over the control panel and it's functions.

Each Sys installation contains at least one server. Each product like Webhost, Email are associated with a specific server.

### Server

All Servers managed by Sys provide Sys Services

### Webhost


clientX - Unix group associated with a User
webX - Unix user associated with a webhost

## Installation

/etc/pi/sys.ini

## Domain



## Webmail

Each Domain may only be hosted in one server. Each server may host several Webmail.

Dovecot authentication is done with [Dict](http://wiki.dovecot.org/AuthDatabase/Dict), so it's used the `ICacheProvider`.

## Webmail User

A webmail user is a 