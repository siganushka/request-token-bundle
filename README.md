# Request token bundle for symfony

[![Build Status](https://github.com/siganushka/request-token-bundle/actions/workflows/ci.yaml/badge.svg)](https://github.com/siganushka/request-token-bundle/actions/workflows/ci.yaml)
[![Latest Stable Version](https://poser.pugx.org/siganushka/request-token-bundle/v/stable)](https://packagist.org/packages/siganushka/request-token-bundle)
[![Latest Unstable Version](https://poser.pugx.org/siganushka/request-token-bundle/v/unstable)](https://packagist.org/packages/siganushka/request-token-bundle)
[![License](https://poser.pugx.org/siganushka/request-token-bundle/license)](https://packagist.org/packages/siganushka/request-token-bundle)

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
    token_generator: Siganushka\RequestTokenBundle\Generator\UniqidTokenGenerator
```
