package org.clickframes.plugins.php;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

import org.clickframes.model.Action;
import org.clickframes.model.ActionType;
import org.clickframes.model.Entity;
import org.clickframes.model.EntityProperty;
import org.clickframes.model.FileInput;
import org.clickframes.model.Form;
import org.clickframes.model.InternalLink;
import org.clickframes.model.Link;
import org.clickframes.model.MultiDropdownInput;
import org.clickframes.model.Output;
import org.clickframes.model.OutputList;
import org.clickframes.model.Page;
import org.clickframes.model.PageParameter;
import org.clickframes.model.SingleUserInput;
import org.clickframes.plugins.ClickframesPlugin;
import org.clickframes.plugins.php.PageEntity.Type;
import org.clickframes.techspec.Context;
import org.clickframes.techspec.Techspec;

/**
 * Copied from Seam2 plugin, customized as necessary.
 * @author Vineet Manohar
 */
public class Plugin extends ClickframesPlugin {
    /**
     * Puts the following variables:
     *
     * generatedEntityFieldsInController - true or false. False is returned only
     * where there is already a param which will implicitly generate the
     * controller field
     */
    @Override
	public void pageContext(Context pageContext, Page page) {
        // generatedEntityFieldsInController - true or false
        {
            List<Entity> retVal = new ArrayList<Entity>();

            Set<Entity> alreadyGeneratedEntities = new HashSet<Entity>();
            // enties for page parameters linked to primary keys are banned
            for (PageParameter pageParameter : page.getParameters()) {
                if (pageParameter.getEntityProperty() != null && pageParameter.getEntityProperty().isPrimaryKey()) {
                    alreadyGeneratedEntities.add(pageParameter.getEntityProperty().getEntity());
                }
            }

            // entities linked to outputs with entity id equal to output id are
            // banned
            for (Output output : page.getOutputs()) {
                if (output.getId().equals(output.getEntity().getId())) {
                    alreadyGeneratedEntities.add(output.getEntity());
                }
            }

            // consider all input entities, but ignore those which are already
            // generated
            for (Entity entity : page.getInputEntities()) {
                if (alreadyGeneratedEntities.contains(entity)) {
                    continue;
                }

                retVal.add(entity);
            }

            pageContext.put("generatedEntityFieldsInController", retVal);
        }

        {
            // page controller
            PageController pageController = new PageController(page);

            // enties for page parameters linked to primary keys
            for (PageParameter pageParameter : page.getParameters()) {
                if (pageParameter.getEntityProperty() != null) {
                    PageEntity pageEntity = PageEntity.create(pageParameter.getEntityProperty().getEntity());
                    pageEntity.setPageParameter(pageParameter);
                    if (pageParameter.getEntityProperty().isPrimaryKey()) {
                        pageEntity.setType(Type.PARAM_PRIMARY_KEY);
                    } else {
                        pageEntity.setType(Type.PARAM_OTHER);
                    }
                    pageController.getPageEntities().add(pageEntity);
                }
            }

            // entities linked to outputs with entity id equal to output id
            for (Output output : page.getOutputs()) {
                PageEntity pageEntity = PageEntity.create(output.getEntity());
                pageEntity.setType(Type.OUTPUT);
                pageEntity.setOutput(output);
                pageController.getPageEntities().add(pageEntity);
            }

            // consider all input entities
            for (Entity entity : page.getInputEntities()) {
                PageEntity pageEntity = PageEntity.create(entity);
                pageEntity.setType(Type.INPUT);
                pageController.getPageEntities().add(pageEntity);
            }

            pageController.analyzeAndSetPageEntityVariableNames();

            // all entity lists which need to be generated
            for (OutputList outputList : page.getOutputLists()) {
                PageEntityList pageEntityList = new PageEntityList();
                pageEntityList.setOutputList(outputList);
                pageEntityList.setId(outputList.getId());
                pageController.getPageEntityLists().add(pageEntityList);
            }

            pageContext.put("controller", pageController);
        }

        // navigation url
        {
            String url = page.getId() + "${pageSuffix}";
            for (int i = 0; i < page.getParameters().size(); i++) {
                PageParameter parameter = page.getParameters().get(i);

                if (i == 0) {
                    url = url + "?";
                } else {
                    url = url + "&";
                }
                url += parameter.getId() + "=${" + parameter.getId() + "}";
            }
            pageContext.put("navigationUrl", url);
            page.setNavigationUrl(url);
        }
    }

    @Override
    public void entityPropertyContext(Context entityPropertyContext, EntityProperty entityProperty) {
        String javaType = "String";

        Techspec techspec = (Techspec) entityPropertyContext.get("techspec");

        if (entityProperty.getType() != null) {
            switch (entityProperty.getType()) {
                case BOOLEAN:
                    javaType = "Boolean";
                    break;
                case DATE:
                    javaType = "java.util.Date";
                    break;
                case ENTITY:
                    javaType = entityProperty.getForeignEntity().getName();
                    break;
                case FILE:
                    javaType = techspec.getPackageName() + ".entity.UploadedFile";
                    break;
                case FLOAT:
                    javaType = "Float";
                    break;
                case INT:
                    javaType = "Integer";
                    break;
                case TEXT:
                default:
                    break;
            }
        }

        if (entityProperty.isMultiple()) {
            javaType = "List<" + javaType + ">";
        }
        entityPropertyContext.put("javaType", javaType);
    }

    /**
     * Adds a variable called "javaType"
     */
    @Override
    public void inputContext(Context inputContext, Form form, SingleUserInput input) {
        String javaType;
        if (input instanceof FileInput) {
            javaType = "UploadedFile";
        } else if (input instanceof MultiDropdownInput) {
            javaType = "List<String>";
        } else {
            javaType = "String";
        }
        inputContext.put("javaType", javaType);
    }

    /**
     * Adds a variable called "queryString" in the link context for outputList
     * links
     */
    @Override
    public void outputListLinkContext(Context outputListLinkContext, Page page, OutputList outputList, Link link) {
        // null, or a query string starting with "?" for example "?id=foo"

        // only for internal links
        if (link.isInternal()) {
            InternalLink internalLink = (InternalLink) link;
            Page targetPage = internalLink.getPage();

            StringBuilder sb = new StringBuilder();
            int count = 0;
            for (PageParameter targetParameter : targetPage.getParameters()) {
                sb.append(".'/'");
                count++;

                // auto fill value if possible
                if (targetParameter.getEntityProperty() != null) {
                    boolean found = false;

                    // 0. The first thing to try would be against the entity
                    // referenced by the outputList
                    if (outputList.getEntity().getId().equals(targetParameter.getEntityProperty().getEntity().getId())) {
                        // the output matches the expected parameter
                    	sb.append(".$"+outputList.getEntity().getId()+"->get"+targetParameter.getEntityProperty().getName()+"()");

                        found = true;
                        continue;
                    }

                    // 1. search all outputs on current page
                    if (found) {
                        continue;
                    }
                    for (Output output : page.getOutputs()) {
                        // see if there is a matching output on this page
                        if (output.getEntity().getId().equals(targetParameter.getEntityProperty().getEntity().getId())) {
                            // the output matches the expected parameter
                        	sb.append(".$outputs['"+output.getId()+"']->get"+targetParameter.getEntityProperty().getName()+"()");

                            found = true;
                            continue;
                        }
                    }

                    // 2. if not found, search all parameters on this page
                    if (found) {
                        continue;
                    }
                    for (PageParameter currentParameter : page.getParameters()) {
                        if (currentParameter.getEntityProperty() != null
                                && currentParameter.getEntityProperty().equals(targetParameter.getEntityProperty())) {
                        	sb.append(".$params['" + currentParameter.getId() + "']");

                            found = true;
                            continue;
                        }
                    }

                    // 3. if not found, search all forms on current page with
                    // semantics of update
                    if (found) {
                        continue;
                    }

                    for (Form form : page.getForms()) {
                        // if no entities ignore this form
                        if (form.getEntities().size() == 0) {
                            continue;
                        }

                        // if any action has semantics of "update"
                        List<Action> updateActions = form.getActionsOfType(ActionType.UPDATE);
                        if (updateActions.size() == 0) {
                            continue;
                        }

                        for (Entity formEntity : form.getEntities()) {
                            // see if there is a matching form entity on this page
                            if (formEntity.getId().equals(targetParameter.getEntityProperty().getEntity().getId())) {
                                // the form entity matches the expected parameter, good!

                                // now find the input which matches the entity property
                                SingleUserInput matchingInput = form.getInputFor(targetParameter.getEntityProperty());

                                sb.append(". $this->_" + form.getId() + "_" + 
                                		matchingInput.getEntityProperty().getEntity().getId() + 
                                		"->get" + matchingInput.getEntityProperty().getName() + "()");

                                found = true;
                                continue;
                            }
                        }
                    }
                }
            }

            outputListLinkContext.put("queryString", sb.toString());
        }
    }

    /**
     * Adds a variable called "queryString" in the link context for internal
     * links
     */
    @Override
    public void pageLinkContext(Context pageLinkContext, Page page, Link link) {
        // null, or a query string starting with "?" for example "?id=foo"

        // only for internal links
        if (link.isInternal()) {
            InternalLink internalLink = (InternalLink) link;
            Page targetPage = internalLink.getPage();

            StringBuilder sb = new StringBuilder();
            int count = 0;
            for (PageParameter targetParameter : targetPage.getParameters()) {
            	sb.append(".'/'");
                count++;

                // auto fill value if possible
                if (targetParameter.getEntityProperty() != null) {
                    boolean found = false;
                    // 1. search all outputs on current page
                    for (Output output : page.getOutputs()) {
                        // see if there is a matching output on this page
                        if (output.getEntity().getId().equals(targetParameter.getEntityProperty().getEntity().getId())) {
                            // the output matches the expected parameter
                        	sb.append(".$outputs['"+output.getId()+"']->get"+targetParameter.getEntityProperty().getName()+"()");
                            found = true;
                            continue;
                        }
                    }

                    // 2. if not found, search all parameters on this page
                    if (found) {
                        continue;
                    }
                    for (PageParameter currentParameter : page.getParameters()) {
                        if (currentParameter.getEntityProperty() != null
                                && currentParameter.getEntityProperty().equals(targetParameter.getEntityProperty())) {
                        	
                        	sb.append(".$params['" + currentParameter.getId() + "']");

                            found = true;
                            continue;
                        }
                    }
                    
                    // 3. if not found, search all forms on current page with
                    // semantics of update
                    if (found) {
                        continue;
                    }

                    for (Form form : page.getForms()) {
                        // if no entities ignore this form
                        if (form.getEntities().size() == 0) {
                            continue;
                        }

                        // if any action has semantics of "update"
                        List<Action> updateActions = form.getUpdateActions();

                        if (updateActions.size() == 0) {
                            continue;
                        }

                        for (Entity formEntity : form.getEntities()) {
                            // see if there is a matching form entity on this
                            // page
                            if (formEntity.getId().equals(targetParameter.getEntityProperty().getEntity().getId())) {
                                // the form entity matches the expected
                                // parameter, good!

                                // now find the input which matches the entity
                                // property
                                SingleUserInput matchingInput = form.getInputFor(targetParameter.getEntityProperty());

                                sb.append(". $this->_" + form.getId() + "_" + 
                                		matchingInput.getEntityProperty().getEntity().getId() + 
                                		"->get" + matchingInput.getEntityProperty().getName() + "()");
                                
                                found = true;
                                continue;
                            }
                        }
                    }
                }
            }

            pageLinkContext.put("queryString", sb.toString());
        }
    }
    
    public void outcomeContext(Context outcomeContext, Link outcome) {
        // null, or a query string starting with "?" for example "?id=foo"
    	
    	Page page = (Page) outcomeContext.get("page");
    	Action action = (Action) outcomeContext.get("action");
    	Form form = (Form) outcomeContext.get("form");

    	logger.info("Processing context for outcome " + outcome.getId());
    	
        // only for internal links
        if (outcome.isInternal()) {
            InternalLink internalLink = (InternalLink) outcome;
            Page targetPage = internalLink.getPage();

            StringBuilder sb = new StringBuilder();
            int count = 0;
            for (PageParameter targetParameter : targetPage.getParameters()) {
            	sb.append(".'/'");
                count++;

                // auto fill value if possible
                if (targetParameter.getEntityProperty() != null) {
                    boolean found = false;
                    
                    if (form != null) {
	                    // 1. check current form for created or updated entity
	                    if (action.getType() == ActionType.CREATE || action.getType() == ActionType.CREATE_OR_UPDATE || action.getType() == ActionType.UPDATE) {
	                    	
	                    	for (Entity formEntity : form.getEntities()) {
	                            // see if there is a matching form entity on this page
	                            if (formEntity.getId().equals(targetParameter.getEntityProperty().getEntity().getId())) {
	                                // the form entity matches the expected parameter, good!
	
	                                sb.append(". $this->_" + form.getId() + "_" + 
	                                		targetParameter.getEntityProperty().getEntity().getId() + 
	                                		"->get" + targetParameter.getEntityProperty().getName() + "()");
	                                
	                                found = true;
	                                continue;

	                            }
	                    	}
	                    }
                    }
                    
                    // 2. check all parameters on this page
                    if (found) {
                        continue;
                    }
                    for (PageParameter currentParameter : page.getParameters()) {
                        if (currentParameter.getEntityProperty() != null
                                && currentParameter.getEntityProperty().equals(targetParameter.getEntityProperty())) {
                        	sb.append(".$this->_" + currentParameter.getId());

                            found = true;
                            continue;
                        }
                    }
                    
                    // 3. check all outputs on this page
                    if (found) {
                        continue;
                    }
                    for (Output output : page.getOutputs()) {
                        // see if there is a matching output on this page
                        if (output.getEntity().getId().equals(targetParameter.getEntityProperty().getEntity().getId())) {
                            // the output matches the expected parameter
                        	sb.append(".$"+output.getId()+"->get"+targetParameter.getEntityProperty().getName()+"()");
                            found = true;
                            continue;
                        }
                    }
                }
            }
            
            outcomeContext.put("queryString", sb.toString());
        }
    }


    /**
     * Puts the following variables:
     *
     * generateEntityFieldInController - true or false. False is returned only
     * where there is already a param which will implicitly generate the
     * controller field
     */
    @Override
    public void outputContext(Context outputContext, Page page, Output output) {
        Entity outputEntity = output.getEntity();
        {
            boolean generateEntityFieldInController = true;
            for (PageParameter pageParameter : page.getParameters()) {
                // if page parameter is linked to the same entity
                // and would produce same entity as this output's id
                if (pageParameter.getEntityProperty() != null
                        && pageParameter.getEntityProperty().getEntity().equals(outputEntity)
                        && pageParameter.getEntityProperty().isPrimaryKey()
                        && pageParameter.getEntityProperty().getEntity().getId().equals(output.getId())) {
                    generateEntityFieldInController = false;
                    break;
                }
            }
            outputContext.put("generateEntityFieldInController", generateEntityFieldInController);
        }
    }
}