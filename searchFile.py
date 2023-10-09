# CSCI 490 Project: Recipe Application
# Searching Algorithm implemented through Python
# The interfacing will be done through PHP at a later date

# Database Data Format:
# Get Once Prepared

# Simple Search Algorithm to be created
# Linear Search should be fine as dataset will not be massive, but will attempt conversion to binary.
# Filters should be a map variable: unordered set of key-value pairs

# test class until database is streamlined
class Recipe:
    def __init__(self, id, userID, name, description, ingredients, instructions, filters):
        self.id = id
        self.userID = userID
        self.name = name
        self.description = description
        self.ingredients = ingredients
        self.instructions = instructions
        self.filters = filters

class searchRecipe:
    def __init__(self, recipes):
        self.recipes = recipes

    def search_by_name(self, query):
        recipeFound = []
        for recipe in self.recipes:
            if query.lower() in recipe.name.lower():
                recipeFound.append(recipe)
        return recipeFound

    def search_by_filter(self, filter):
        recipeFound = []
        for recipe in self.recipes:
            if filter in recipe.filters:
                recipeFound.append(recipe)
            return recipeFound

# Test basic search later.