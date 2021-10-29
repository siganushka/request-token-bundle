# Request token bundle for symfony

Add `X-Request-Id` for request/response headers.

see: [What is the X-REQUEST-ID http header](https://stackoverflow.com/questions/25433258/what-is-the-x-request-id-http-header)

### Installation

```bash
$ composer require siganushka/request-token-bundle
```

### Configuration

```yaml
# ./config/packages/siganushka_request_token.yaml

siganushka_request_token:
    enabled: true
    header_name: X-Request-Id
    token_generator: siganushka_request_token.generator.random_bytes
```
