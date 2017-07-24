# Testing

The SDK tests against various entities that are stored in some spaces Contentful. Here is an overview of what these entities are, where you can find them, and what their purpose is.

Spaces:

- Contentful Example API (`cfexampleapi`): this is a read-only space, and is to be used only for testing `GET` requests.
- PHP CMA (`34luz0flcmxt`): this space can be used for testing requests for all HTTP methods. Every test should clean up after itself, meaning that the space will be left in the same conditions as it was before the test was executed.

The user used to record the tests is specific to the SDK. The user has access to a test organization with the ID `4Q3Lza73mxcjmluLU7V5EG`. Any spaces that appear in that organization are like vestiges of failed test runs.

## Contentful Example API (`cfexampleapi`)

### Assets

| ID | Location | Description |
| - | - | - |
| `nyancat` | `AssetTest::testGetAsset` | Testing of properties |
| `*` | `AssetTest::testGetAssets` | Retrieval of all assets |


### Content Types

| ID | Location | Description |
| - | - | - |
| `cat` | `ContentTypeTest::testGetContentType`, `ContentTypeTest::testGetPublishedContentType` | Testing of properties |
| `*` | `ContentTypeTest::testGetContentTypes`. `ContentTypeTest::testGetPublishedContentTypes` | Retrieval of all content types |


### Entries

| ID | Location | Description |
| - | - | - |
| `nyancat` | `EntryTest::testGetEntry` | Testing of properties |
| `*` | `EntryTest::testGetEntries` | Retrieval of all entries |


### Locales

| ID | Location | Description |
| - | - | - |
| `2oQPjMCL9bQkylziydLh57` | `LocaleTest::testGetLocale` | Testing of properties |
| `*` | `LocaleTest::testGetLocales` | Retrieval of all locales |


## PHP CMA (`34luz0flcmxt`)

### Assets

| ID | Location | Description |
| - | - | - |
| - | `AssetTest::testCreateUpdateProcessPublishUnpublishArchiveUnarchiveDelete`, `AssetTest::testCreateAssetWithGivenId` | Cleans up after itself |
| - | `ErrorTest::testBadRequestError` | Expects `BadRequestException` |
| - | `ErrorTest::testVersionMismatchError` | Expects `VersionMismatchException`, cleans up after itself |

### Content Types

| ID | Location | Description |
| - | - | - |
| - | `ContentTypeTest::testCreateUpdateActivateDeleteContentType`, `ContentTypeTest::testCreateContentTypeWithGivenId` | Cleans up after itself |


### Entries

| ID | Location | Description |
| - | - | - |
| - | `EntryTest::testCreateUpdatePublishUnpublishArchiveUnarchiveDelete`, `EntryTest::testCreateEntryWithGivenId` | Cleans up after itself |
| `2cOd0Aho3WkowMgk2C02iy` | `EntryTest::testCreateEntryWithoutFields` | The entry was saved without proving values for its fields, therefore Contentful will not return the property `fields` |
| `3LM5FlCdGUIM0Miqc664q6` | `SnapshotTest::testGetEntrySnapshot`, `SnapshotTest::testGetEntrySnapshots` | Used for testing entry snapshots |
| - | `WebhookTest::testWebhookEventsFiredAndLogged` | Creates and deletes entries for testing webhooks |


### Locales

| ID | Location | Description |
| - | - | - |
| - | `LocaleTest::testCreateUpdateDelete` | Cleans up after itself |
| - | `ErrorTest::testUnknownKeyError` | Expects `UnknownKeyException` |
| - | `ErrorTest::testMissingKeyError` | Expects `MissingKeyException` |
| - | `ErrorTest::testValidationFailedError` | Expects `ValidationFailedException` |
| `6khdsfQbtrObkbrgWDTGe8` | `ErrorTest::testDefaultLocaleNotDeletableError` | Expects `DefaultLocaleNotDeletableException` |
| `71wkZKqgktY9Uzg76CtsBK` | `ErrorTest::testFallbackLocaleNotDeletableError` | Expects `FallbackLocaleNotDeletableException` |
| `71wkZKqgktY9Uzg76CtsBK` | `ErrorTest::testFallbackLocaleNotRenameableError` | Expects `FallbackLocaleNotRenameableException` |


### Roles

| ID | Location | Description |
| - | - | - |
| `6khUMmsfVslYd7tRcThTgE` | `RoleTest::testGetRole` | Testing of properties |


### Snapshots

| ID | Location | Description |
| - | - | - |
| `3omuk8H8M8wUuqHhxddXtp` | `SnapshotTest::testGetEntrySnapshot` | Testing of properties |


### Webhooks

| ID | Location | Description |
| - | - | - |
| `3tilCowN1lI1rDCe9vhK0C` | `WebhookTest::testGetWebhook` | Testing of properties |
| `*` | `WebhookTest::testGetWebhooks` | Retrieval of all webhooks |
| - | `WebhookTest::testCreateWebhook` | Webhook used in `WebhookTest::testWebhookEventsFiredAndLogged`, cleaned up in `WebhookTest::testDeleteWebhook` |



## No space

### Organizations

| ID | Location | Description |
| - | - | - |
| `4Q3Lza73mxcjmluLU7V5EG` | `OrganizationTest::testGetOrganizations`, `OrganizationTest::testGetOrganizationsWithQuery` | Testing of properties |
