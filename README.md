# Update Helper

Class library to contain some common behaviours during update hooks.

## Contents

- `\Discoverygarden\UpdateHelper`
  - Contains the single method `::ensureModuleEnabled()` which accepts the name of a module to ensure is enabled, which is a recurring operation over the lifetime of modules as dependencies change, as Drupal does not presently account for new additions to `{extension}.info.yml`. Additionally, it may be desirable to "soften" the inclusion of behaviours that are extracted from a given module such that even if `.info.yml` _was_ enforced, it might not be desirable to have new installations enforce the same module state.
