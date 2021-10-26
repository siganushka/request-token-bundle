# Request token bundle for symfony

Add `X-Request-Id` for request/response headers.

see: [What is the X-REQUEST-ID http header][1]

### Installation

```bash
$ composer require siganushka/request-token-bundle
```

### Configuration

```yaml
# ./config/packages/siganushka_request_token.yaml

siganushka_request_token:
    request_header:
        enabled: true
        name: 'X-Request-Id'
    response_header:
        enabled: true
        name: 'X-Request-Id'

```

  [1]: https://stackoverflow.com/questions/25433258/what-is-the-x-request-id-http-header