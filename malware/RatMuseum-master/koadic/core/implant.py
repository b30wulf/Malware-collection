import core.plugin

class Implant(core.plugin.Plugin):
    def __init__(self, shell):
        super(Implant, self).__init__(shell)
        self.options.register("ZOMBIE", "ALL", "the zombie to target")
        self.options.register("IGNOREADMIN", "false", "ignore session elevation restrictions", enum=["true", "false"], advanced=True)
        self.options.register("IGNOREBUILD", "false", "ignore build number", enum=["true", "false"], advanced=True)
